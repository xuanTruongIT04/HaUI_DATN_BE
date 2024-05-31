<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\UserService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Users\UpdateInforRequest;
use App\Models\User;

class UserController extends Controller
{
    //
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateInfor(UpdateInforRequest $request)
    {
        try {
            $update = $request->validated();
            if (Auth::guard("user")) {
                $id = Auth::guard("user")->id();
            }
            $update = array_filter($update, function ($value) {
                return $value !== null;
            });

            $isUpdate = $this->userService->update($id, $update);
            if ($isUpdate) {
                return $this->sendSuccessResponse("Bạn đã cập nhật thông tin cá nhân thành công!");
            }
            return $this->sendFailResponse("Bạn đã cập nhật thông tin cá nhân thất bại, xin vui lòng thử lại");
        } catch (\Exception $e) {
            return $this->sendFailResponse("Bạn đã cập nhật thông tin cá nhân thất bại, xin vui lòng thử lại");
        }
    }

    public function checkInfor()
    {
        try {
            if (Auth::guard("user")->check()) {
                $id = Auth::guard("user")->id();
                $user = $this->userService->checkInfor($id);
                if ($user)
                    return $this->sendSuccessResponse(["info" => $user]);
                else
                    return $this->sendSuccessResponse(["info" => false]);
            } else {
                return $this->sendFailResponse("Bạn chưa đăng nhập");
            }
        } catch (\Exception $e) {
            return $this->sendFailResponse("Bạn đã kiểm tra thông tin cá nhân thất bại, xin vui lòng thử lại");
        }
    }


    public function showInfor()
    {
        try {
            if (Auth::guard("user")->check()) {
                $id = Auth::guard("user")->id();
                $userInfor = $this->userService->getInfor($id);
                return $this->sendSuccessResponse($userInfor);
            } else {
                return $this->sendFailResponse("Bạn chưa đăng nhập");
            }
        } catch (\Exception $e) {
            return $this->sendFailResponse("Lỗi khi lấy thông tin cá nhân");
        }
    }

    public function updateRememberToken(Request $request)
    {
        if (Auth::guard("user")->check())
            $user = Auth::guard("user")->user();
        if ($user) {
            $user->remember_token = $request->input('remember_token');
            $user->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function checkRememberToken(Request $request)
    {
        $token = $request->input('token');
        $remember_token = Auth::guard("user") ? Auth::guard("user")->user()->remember_token : "";
        if ($remember_token == $token) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
