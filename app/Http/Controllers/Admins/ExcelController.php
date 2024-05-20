<?php

namespace App\Http\Controllers\Admins;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    protected $orders, $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function exportOrders(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $orders = $this->productService->getProductByDate($startDate, $endDate);
            info($orders);
            // dd($orders);

            if ($orders->isNotEmpty()) {
                $filename = 'orders_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
                return Excel::download(new OrdersExport($orders), $filename);
            } else {
                return redirect("product/track-product-sold")->with('statusFail', "Không có đơn hàng nào trong khoảng thời gian này");
            }
        } catch (\Exception $e) {
            return redirect("product/track-product-sold")->with('statusFail', "Export thất bại, do lỗi: " . $e->getMessage());
        }
    }
}
