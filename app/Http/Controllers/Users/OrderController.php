<?php

namespace App\Http\Controllers\Users;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

use App\Jobs\SendOrderSuccessEmailJob;
use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessEmail;
use App\Services\OrderService;
use App\Services\CartService;
use App\Services\BillService;
use App\Helpers\Constant;
use App\Models\Cart;

class OrderController extends Controller
{
    protected $orderService, $cartService, $billService;

    public function __construct(OrderService $orderService, CartService $cartService, BillService $billService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
        $this->billService = $billService;
    }

    public function add(Request $request)
    {
        try {
            $idUser = Auth::guard('user')->id();
            $dataCreate = json_decode($request->getContent(), true);
            $status = $this->orderService->create($dataCreate);
            if ($status) {
                return $this->sendSuccessResponse(['create' => true]);
            } else {
                return $this->sendErrorResponse(['create' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $userId = Auth::guard('user')->id();
            $cart = Cart::where("user_id", $userId)->orderByDesc('id')->first();
            if ($cart) {
                $statusOrder = array_keys(Constant::STATUS_ORDER);
                $data = ['cart_id' => $cart->id];

                $idOrder = $this->orderService->findByCartID($data['cart_id']);
                if ($idOrder) {
                    $data['status'] = $statusOrder[3];
                } else {
                    $data['status'] = $statusOrder[0];
                }
                $status = $this->orderService->updateOrCreate($data);
                if ($status) {
                    return $this->sendSuccessResponse(['create' => true]);
                }
            }
            return $this->sendErrorResponse(['create' => false]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function submitOrder(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            $idUser = $user->id;
            $dataSubmitOrder = json_decode($request->getContent(), true);
            $idBill = $this->orderService->submitOrder($idUser, $dataSubmitOrder);
            if ($idBill) {
                $billData = $this->billService->getInfoFromBill($idBill);
                //SEND MAIL HERE
                dispatch(new SendOrderSuccessEmailJob($billData, $user));
                $orderId = $billData->order_id;
                $this->orderService->swapInfoStore($user, $orderId);
                return $this->sendSuccessResponse($billData);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function getStatus()
    {
        try {
            $userId = Auth::guard('user')->id();
            $cart = Cart::where("user_id", $userId)->first();
            if ($cart) {
                $order = $this->orderService->checkStatusOC($cart->id);
                if ($order) {
                    return $this->sendSuccessResponse(['status' => true]);
                }
            }
            return $this->sendSuccessResponse(['status' => false]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function getInfoOrder()
    {
        try {
            $userId = Auth::guard('user')->id();
            $cart = Cart::where("user_id", $userId)->orderByDesc('id')->first();
            if ($cart) {
                $cartId = $cart->id;
                $order = $this->orderService->getInfoOrder($cartId);
                if ($order) {
                    return $this->sendSuccessResponse($order);
                }
            }
            return $this->sendSuccessResponse(['info' => false]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function getListOrderByUser()
    {
        try {
            $userId = Auth::guard('user')->id();
            $listOrder = $this->orderService->getListOrderByUser($userId);
            if (!empty($listOrder)) {
                return $this->sendSuccessResponse($listOrder);
            }
            return $this->sendSuccessResponse(['info' => false]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }
}
