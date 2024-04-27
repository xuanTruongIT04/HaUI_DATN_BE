<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Brand\EditBrandRequest;
use App\Http\Requests\Admins\Brand\StoreBrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    //
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    function list(Request $request)
    {
        $brands = $this->brandService->all();
        $countBrands = $brands->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->brandService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $brands = $this->brandService->searchBrands($keyWord, 20, $statusData, $where);
        $brands->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countBrandsSearch = $brands->total();
        $cntBrandActive = $this->brandService->countBrands();
        $cntBrandLicensed = $this->brandService->countBrands("without", $listCondition[0]);
        $cntBrandPending = $this->brandService->countBrands("without", $listCondition[1]);
        $cntBrandTrashed = $this->brandService->countBrands("only");
        // Merge to array count status
        $countBrandStatus = [$cntBrandActive, $cntBrandLicensed, $cntBrandPending, $cntBrandTrashed];

        return view("brand.list", compact('brands', "countBrandStatus", "listAct", "countBrands", "countBrandsSearch"));
    }

    public function add()
    {
        return view('brand.add');
    }

    public function store(StoreBrandRequest $request)
    {
        $dataCreate = $request->validated();

        $name = $request->input("name");

        $this->brandService->create($dataCreate);
        return redirect("brand/list")->with('statusSuccess', "Bạn đã thêm nhãn hiệu tên '$name' thành công!");

    }

    public function edit($id)
    {
        $brand = $this->brandService->find($id);

        return view('brand.edit', compact("brand"));
    }

    public function update(EditBrandRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        $status = $dataUpdate['status'];
        // Update super brand
        $this->brandService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update brand other
        $brandName = $this->brandService->find($id)->name;
        if ($status == $listCondition[2]) {
            // dd($status);
            $this->brandService->delete($id);
        }
        return redirect("brand/list")->with('statusSuccess', "Bạn đã cập nhật thông tin nhãn hiệu tên '$brandName' thành công!");
    }

    public function delete($id)
    {
        $brand = Brand::withTrashed()->where("id", $id)->first();
        $brand_id = $brand->id;
        $name = $brand->name;

        if (empty($brand->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->brandService->update($id, $dataUpdate);
            $this->brandService->delete($id);

            return redirect("brand/list")->with("status", "Bạn đã xoá tạm thời nhãn hiệu tên {$name} thành công!");
        } else {
            $brand->forceDelete();
            return redirect("brand/list")->with("status", "Bạn đã xoá vĩnh viễn nhãn hiệu tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->where("id", $id)->first();
        $brand->restore();
        $name = $brand->name;

        if (empty($brand->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->brandService->update($id, $dataUpdate);
        }
        return redirect("brand/list")->with("status", "Bạn đã khôi phục nhãn hiệu tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->brandService->action($requests);
    }
}