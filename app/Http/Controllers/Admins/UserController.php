<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\User\EditUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    function list(Request $request)
    {
        $users = $this->userService->all();
        $countUsers = $users->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->userService->constraintAction($request);
        $where = $constraintAction['where'];
        $listAct = $constraintAction['listAct'];
        // Handle action with constaint
        $users = $this->userService->searchUsers($keyWord, 20, $where);
        $users->withQueryString();

        $listCondition = array_keys(Constant::STATUS_USER);
        // Get number record by status
        $countUsersSearch = $users->total();
        $cntUserActive = $this->userService->countUsers($listCondition[0]);
        $cntUserPending = $this->userService->countUsers($listCondition[1]);
        $cntUserBlocked = $this->userService->countUsers($listCondition[2]);
        // Merge to array count status
        $countUserStatus = [$cntUserActive, $cntUserPending, $cntUserBlocked];

        return view("user.list", compact('users', "countUserStatus", "listAct", "countUsers", "countUsersSearch"));
    }

    public function edit($id)
    {
        $user = $this->userService->find($id);

        return view('user.edit', compact("user"));
    }

    public function update(EditUserRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        // Update super user
        $this->userService->update($id, $dataUpdate);
        // Update user other
        $userName = $this->userService->find($id)->first_name;

        return redirect("user/list")->with('statusSuccess', "Bạn đã cập nhật thông tin khách hàng tên '$userName' thành công!");
    }

    public function action(Request $requests)
    {
        return $this->userService->action($requests);
    }
}