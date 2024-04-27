<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Cart\EditCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    function list(Request $request)
    {
        $carts = $this->cartService->all();
        $countCarts = $carts->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->cartService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $listAct = $constraintAction['listAct'];
        // Handle action with constaint
        $carts = $this->cartService->searchCarts($keyWord, 20, $where);
        $carts->withQueryString();

        $listCondition = array_keys(Constant::STATUS_CART);
        // Get number record by status
        $countCartsSearch = $carts->total();
        $cntCartActive = $this->cartService->countCarts($listCondition[0]);
        $cntCartPaid = $this->cartService->countCarts($listCondition[1]);
        $cntCartExpired = $this->cartService->countCarts($listCondition[2]);
        $cntCartCancelled = $this->cartService->countCarts($listCondition[3]);
        // Merge to array count status
        $countCartStatus = [$cntCartActive, $cntCartPaid, $cntCartExpired, $cntCartCancelled];
        return view("cart.list", compact('carts', "countCartStatus", "listAct", "countCarts", "countCartsSearch"));
    }

    public function edit($id)
    {
        $cart = $this->cartService->find($id);
        $listDetailCart = $this->cartService->getDetailCart($id);
        return view('cart.edit', compact("cart", "listDetailCart"));
    }

    public function update(EditCartRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        // Update super cart
        $this->cartService->updateCart($id, $dataUpdate);

        return redirect("cart/list")->with('statusSuccess', "Bạn đã cập nhật thông tin giỏ hàng thành công!");
    }

    public function action(Request $requests)
    {
        return $this->cartService->action($requests);
    }
}