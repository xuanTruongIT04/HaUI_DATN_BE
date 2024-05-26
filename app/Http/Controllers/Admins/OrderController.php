<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Order\EditOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    function list(Request $request)
    {
        $orders = $this->orderService->all();
        $countOrders = $orders->count();

        // Search information
        $keyWord = "";

        if ($request->input("key_word")) {
            $keyWord = $request->input("key_word");
        }
        // Get constraint action
        $constraintAction = $this->orderService->constraintAction($request);
        $where = $constraintAction['where'];
        $listAct = $constraintAction['listAct'];
        // Handle action with constaint
        $orders = $this->orderService->searchOrders($keyWord, 20, $where);
        $orders->withQueryString();

        $listCondition = array_keys(Constant::STATUS_ORDER);
        // Get number record by status
        $countOrdersSearch = $orders->total();
        $cntOrderOrdered = $this->orderService->countOrders($listCondition[0]);
        $cntOrderProcessing = $this->orderService->countOrders($listCondition[1]);
        $cntOrderPaid = $this->orderService->countOrders($listCondition[2]);
        $cntOrderCancelled = $this->orderService->countOrders($listCondition[3]);
        // Merge to array count status
        $countOrderStatus = [$cntOrderOrdered, $cntOrderProcessing, $cntOrderPaid, $cntOrderCancelled];

        return view("order.list", compact('orders', "countOrderStatus", "listAct", "countOrders", "countOrdersSearch"));
    }

    public function edit($id)
    {
        $order = $this->orderService->find($id);

        return view('order.edit', compact("order"));
    }

    public function update(EditOrderRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        // Update super order
        $this->orderService->update($id, $dataUpdate);
        // Update order other
        $customerName = $this->orderService->find($id)?->cart?->user->first_name;

        return redirect("order/list")->with('statusSuccess', "Bạn đã cập nhật thông tin đơn hàng của khách hàng có tên '$customerName' thành công!");
    }

    public function detail($id)
    {
        $order = $this->orderService->find($id);
        $listDetailOrder = $this->orderService->getDetailOrder($id);
        $coupon = $this->orderService->getCoupon($id);
        return view("order.detail", compact("order", "listDetailOrder", "coupon"));
    }

    public function detailUpdate(EditOrderRequest $requests, $id)
    {
        $order_code = $this->orderService->find($id)->code;

        Order::where('id', $id)->update([
            'status' => $requests->input("status"),
        ]);
        return redirect("order/list")->with("status", "Đã cập nhật thông tin đơn hàng có mã {$order_code} thành công");
    }

    public function action(Request $requests)
    {
        return $this->orderService->action($requests);
    }
}
