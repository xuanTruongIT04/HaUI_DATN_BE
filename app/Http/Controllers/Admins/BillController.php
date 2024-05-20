<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Bill\EditBillRequest;
use App\Models\Bill;
use App\Services\BillService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class BillController extends Controller
{
    //
    protected $billService, $orderService;

    public function __construct(BillService $billService, OrderService $orderService)
    {
        $this->billService = $billService;
        $this->orderService = $orderService;
    }

    function list(Request $request)
    {
        $bills = $this->billService->all();
        $countBills = $bills->count();

        // Search information
        $keyWord = "";

        if ($request->input("key_word")) {
            $keyWord = $request->input("key_word");
        }
        // Get constraint action
        $constraintAction = $this->billService->constraintAction($request);
        $where = $constraintAction['where'];
        $listAct = $constraintAction['listAct'];
        // Handle action with constaint
        $bills = $this->billService->searchBills($keyWord, 20, $where);
        $bills->withQueryString();

        $listCondition = array_keys(Constant::STATUS_ORDER);
        // Get number record by status
        $countBillsSearch = $bills->total();
        $cntBillBilled = $this->billService->countBills($listCondition[0]);
        $cntBillProcessing = $this->billService->countBills($listCondition[1]);
        $cntBillPaid = $this->billService->countBills($listCondition[2]);
        $cntBillCancelled = $this->billService->countBills($listCondition[3]);
        // Merge to array count status
        $countBillStatus = [$cntBillBilled, $cntBillProcessing, $cntBillPaid, $cntBillCancelled];

        return view("bill.list", compact('bills', "countBillStatus", "listAct", "countBills", "countBillsSearch"));
    }

    public function edit($id)
    {
        $bill = $this->billService->find($id);

        return view('bill.edit', compact("bill"));
    }

    public function update(EditBillRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        // Update super bill
        $this->billService->update($id, $dataUpdate);
        // Update bill other
        $billCode = $this->billService->find($id)->order->code;

        return redirect("bill/list")->with('statusSuccess', "Bạn đã cập nhật thông tin hoá đơn mã đơn hàng là '{$billCode}' thành công!");
    }

    public function detail($id)
    {
        $bill = $this->billService->find($id);
        $order = $bill->order;
        $listDetailOrder = $this->billService->getDetailOrder($id);
        $coupon = $this->billService->getCoupon($id);
        $user = $this->billService->getUser($id);
        return view("bill.detail", compact("bill", "listDetailOrder", "coupon", "user", "order"));
    }

    public function detailUpdate(EditBillRequest $requests, $id)
    {
        $billCode = $this->billService->find($id)->order->code;
        $dataUpdateBill = [
            'status' => $requests->input("status")
        ];

        $this->billService->update($id, $dataUpdateBill);
        return redirect("bill/list")->with("status", "Đã cập nhật thông tin hoá đơn có mã đơn hàng là '{$billCode}' thành công");
    }

    public function action(Request $requests)
    {
        return $this->billService->action($requests);
    }
}
