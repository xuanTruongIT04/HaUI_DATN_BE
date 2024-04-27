<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\BillService;

class BillController extends Controller
{
    protected $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    public function getInfoFromBill($idBill)
    {
        try {
            $billData = $this->billService->getInfoFromBill($idBill);
            return $this->sendSuccessResponse($billData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}