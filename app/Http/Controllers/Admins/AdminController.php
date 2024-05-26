<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Admin\EditAdminRequest;
use App\Http\Requests\Admins\Admin\ResetPasswordAdminRequest;
use App\Http\Requests\Admins\Admin\StoreAdminRequest;
use App\Models\Admin;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    function list(Request $request)
    {
        $admins = $this->adminService->all();
        $countAdmins = $admins->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }
        // Get constraint action
        $constraintAction = $this->adminService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $condition = $constraintAction['condition'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $admins = $this->adminService->searchAdmins($keyWord, 20, $condition, $where);
        $admins->withQueryString();
        // Get number record by status
        $countAdminsSearch = $admins->total();

        $listCondition = array_keys(Constant::STATUS_ADMIN);
        $cntAdminActive = $this->adminService->countAdmins();
        $cntAdminLicensed = $this->adminService->countAdmins("without", $listCondition[0]);
        $cntAdminPending = $this->adminService->countAdmins("without", $listCondition[1]);
        $cntAdminTrashed = $this->adminService->countAdmins("only");

        // Merge to array count status
        $countAdminStatus = [$cntAdminActive, $cntAdminLicensed, $cntAdminPending, $cntAdminTrashed];

        return view("admin.list", compact('admins', "countAdminStatus", "listAct", "countAdmins", "countAdminsSearch"));
    }

    public function add()
    {
        return view('admin.add');
    }

    public function store(StoreAdminRequest $request)
    {
        $dataCreate = $request->validated();

        $name = $request->input("name");
        $dataCreate['password'] = bcrypt($request->input("password"));

        $this->adminService->create($dataCreate);
        return redirect("admin/list")->with('statusSuccess', "Bạn đã thêm quản trị viên tên '$name' thành công!");

    }

    public function edit($id)
    {
        $admin = $this->adminService->find($id);
        $genders = array_keys(Constant::GENDER);
        return view('admin.edit', compact("admin", "genders"));
    }

    public function update(EditAdminRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        //Validate avatar admin
        if ($request->hasFile('avatar')) {
            $avatar = uploadFileHelper($request, 'avatar');
        } else {
            if ($request->avatar && $request->avatar != 'null') {
                unset($avatar);
            } else {
                $avatar = '';
            }
        }
        if (isset($avatar)) {
            $dataUpdate['avatar'] = "storage/" . $avatar;
        }

        $idAuth = Auth::user()->id;
        // Update super admin
        $this->adminService->update($id, $dataUpdate);
        if (!empty($dataUpdate['status']))
        $status = $dataUpdate['status'];
        $listCondition = array_keys(Constant::STATUS_ADMIN);


        if ($idAuth == $id) {
            return redirect("admin/list")->with('statusSuccess', "Bạn đã cập nhật thông tin cá nhân thành công!");
        }

        // Update admin other
        $adminName = $this->adminService->find($id)->name;

        if (!empty($dataUpdate['status'])) {
            // If update status is trashed
            if ($status == $listCondition[2]) {
                $this->adminService->delete($id);
            }
        }

        return redirect("admin/list")->with('statusSuccess', "Bạn đã cập nhật thông tin thành viên tên '$adminName' thành công!");
    }

    public function editPassword($id)
    {
        $admin = $this->adminService->find($id);
        return view('admin.editPassword', compact("admin"));
    }

    public function updatePassword(ResetPasswordAdminRequest $request, $id)
    {
        $passwordOldDb = $this->adminService->find($id)->first()->password;
        $passwordOldInput = $request->input("passwordOld");

        $passwordNew = $request->input("password");
        $dataUpdate['password'] = Hash::make($passwordNew);

        if (Hash::check($passwordOldInput, $passwordOldDb)) {
            $this->adminService->update($id, $dataUpdate);
            return back()->with('statusSuccess', "Bạn đã cập nhật mật khẩu thành công!");
        } else {
            return back()->with('statusFail', "Mật khẩu cũ không chính xác, vui lòng thử lại!");
        }
    }

    public function delete($id)
    {
        if (Auth::id() != $id) {
            $admin = Admin::withTrashed()->where("id", $id)->first();
            $fullname = $admin->name;

            if (empty($admin->deleted_at)) {
                $listCondition = array_keys(Constant::STATUS_ADMIN);
                $dataUpdate['status'] = $listCondition[2];

                $this->adminService->update($id, $dataUpdate);
                $this->adminService->delete($id);

                return redirect("admin/list")->with("status", "Bạn đã vô hiệu hoá tạm thời thành viên tên {$fullname} thành công!");
            } else {
                $admin->forceDelete();
                return redirect("admin/list")->with("status", "Bạn đã vô hiệu hoá vĩnh viễn thành viên tên {$fullname} thành công!");
            }
        } else {
            return redirect("admin/list")->with("status", "Bạn không thể tự vô hiệu hoá chính mình ra khỏi hệ thống!");
        }
    }

    public function restore($id)
    {
        if (Auth::id() != $id) {
            $admin = Admin::onlyTrashed()->where("id", $id)->first();
            $admin->restore();
            $fullname = $admin->name;

            if (empty($admin->deleted_at)) {
                $listCondition = array_keys(Constant::STATUS_ADMIN);
                $dataUpdate['status'] = $listCondition[1];
                $this->adminService->update($id, $dataUpdate);
            }
            return redirect("admin/list")->with("status", "Bạn đã khôi phục thành viên tên '$fullname' thành công");
        } else {
            return redirect("admin/list")->with("status", "Bạn không thể tự khôi phục chính mình trong hệ thống");
        }

    }

    public function action(Request $requests)
    {
        return $this->adminService->action($requests);
    }

}
