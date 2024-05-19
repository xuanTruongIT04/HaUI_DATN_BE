<?php

namespace App\Http\Controllers\Admins;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ProductService;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function exportOrders(): BinaryFileResponse
    {
        $orders = $this->productService->getProductSellInDay();
        $filename = 'orders_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new OrdersExport($orders), $filename);
    }
}
