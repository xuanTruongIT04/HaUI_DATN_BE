<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Product\EditProductRequest;
use App\Http\Requests\Admins\Product\StoreProductRequest;
use App\Http\Requests\Admins\Product\EditImageproductRequest;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Throwable;

class ProductController extends Controller
{
    //
    protected $productService;
    protected $imageService;

    public function __construct(ProductService $productService, ImageService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
    }

    function list(Request $request)
    {
        $products = $this->productService->all();
        $countProducts = $products->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->productService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $products = $this->productService->searchProducts($keyWord, 20, $statusData, $where);
        $cntProductAboutToExpiry = $this->productService->countProductExpireds();
        $cntProductNeedMore = $this->productService->countProductNeedMore();
        $products->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countProductsSearch = $products->total();
        $cntProductActive = $this->productService->countProducts();
        $cntProductLicensed = $this->productService->countProducts("without", $listCondition[0]);
        $cntProductPending = $this->productService->countProducts("without", $listCondition[1]);
        $cntProductTrashed = $this->productService->countProducts("only");
        // Merge to array count status
        $countProductStatus = [$cntProductActive, $cntProductLicensed, $cntProductPending, $cntProductTrashed, $cntProductTrashed];

        return view("product.list", compact('products', "countProductStatus", "listAct", "countProducts", "countProductsSearch", "cntProductAboutToExpiry", "cntProductNeedMore"));
    }

    function trackProductSold(Request $request)
    {
        $orderInDays = $this->productService->getProductSellInDay();

        if ($request->input("start_date") && $request->input("end_date")) {
            $startDate = $request->input("start_date");
            $endDate = $request->input("end_date");
            $orderInDays = $this->productService->getProductByDate($startDate, $endDate);
        }

        $countProducts = 0;
        if (!empty($orderInDays)) {
            foreach ($orderInDays as $itemOrderInDays) {
                $detailOrder = $itemOrderInDays?->detailOrders;
                if (!empty($detailOrder)) {
                    foreach ($detailOrder as $itemDetailOrder) {
                        $product = $itemDetailOrder?->product;
                        if (!empty($product)) {
                            $countProducts++;
                        }
                    }
                }
            }
        }

        if (!empty($request->input("export_excel")) && !empty($orderInDays)) {
            $response = $this->productService->exportExcel($orderInDays);
            if($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
                Session::flash('statusSuccess', 'Xuất file excel thành công!');
            }
        } else if(empty($orderInDays)) {
            Session::flash('statusFail', 'Xuất file excel thất bại, không tồn tại đơn hàng nào ');

        }


        // Handle action with constaint
        $cntProductAboutToExpiry = $this->productService->countProductExpireds();
        $cntProductNeedMore = $this->productService->countProductNeedMore();
        return view("product.trackProductSold", compact('orderInDays', "countProducts", "cntProductAboutToExpiry", "cntProductNeedMore"));
    }

    public function add()
    {
        return view('product.add');
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $dataCreateProduct = $request->validated();
            $name = $dataCreateProduct["name"];
            // dd($dataCreateProduct);

            //Create product table
            if ($product = $this->productService->create($dataCreateProduct)) {
                $idProduct = $product->id;
                return redirect("image/add/" . $idProduct)->with('statusSuccess', "Thành công! Hãy tiếp tục thêm ảnh cho sản phẩm '$name' !");
            }
            return redirect("product/list")->with('statusFail', "Bạn đã thêm sản phẩm tên '$name' thất bại!");
        } catch (Throwable $e) {
            return redirect("product/list")->with('statusFail', "Thao tác thêm mới thất bại, do lỗi: " . $e);
        }
    }

    public function edit($id)
    {
        $product = $this->productService->find($id);

        return view('product.edit', compact("product"));
    }

    public function update(EditProductRequest $request, $productId)
    {
        try {
            $dataUpdateProduct = $request->validated();

            $status = $dataUpdateProduct['status'];
            $name = $dataUpdateProduct["name"];

            // Update super product
            $this->productService->update($productId, $dataUpdateProduct);

            // Update product other
            $listCondition = array_keys(Constant::STATUS);
            if ($status == $listCondition[2]) {
                $this->productService->delete($productId);
            }
            return redirect("product/list")->with('statusSuccess', "Bạn đã cập nhật thông tin sản phẩm tên '$name' thành công!");
        } catch (Throwable $e) {
            return redirect("product/list")->with('statusFail', "Thao tác thêm mới thất bại, do lỗi: " . $e);
        }
    }

    public function updateImage(EditImageproductRequest $request, $productId)
    {
        $dataUpdateImageProduct = $request->validated();
        $productName = $this->productService->find($productId) ? $this->productService->find($productId)->name : "";

        $dataCheckExists['product_id'] = $productId;
        $dataCheckExists['color_id'] = $dataUpdateImageProduct['color_id'];

        // Update image table
        if ($request->hasFile("thumb")) {
            //Validate thumb slide
            $thumb = uploadFileHelper($request, 'thumb');
        } else {
            if ($request->thumb && $request->thumb != 'null') {
                unset($thumb);
            } else {
                $thumb = '';
            }
        }
        if (!empty($thumb)) {
            $dataUpdateOrCreate['link'] = "storage/" . $thumb;
        }
        $dataUpdateOrCreate['level'] = Constant::LEVEL_IMAGE[0];
        $dataUpdateOrCreate['description'] = "";
        $dataUpdateOrCreate['product_id'] = $dataCheckExists['product_id'];
        $dataUpdateOrCreate['color_id'] = $dataCheckExists['color_id'];
        $this->imageService->updateOrCreate($dataUpdateOrCreate);

        // Update multi images
        $dataUpdateOrCreate['level'] = Constant::LEVEL_IMAGE[1];

        if ($request->hasFile("list_thumb")) {
            $listImages = $request->file("list_thumb");
            foreach ($listImages as $image) {
                $listThumb = uploadMultiFileHelper($request, 'list_thumb');
            }
            if (!empty($listThumb)) {
                foreach ($listThumb as $thumb) {
                    $dataUpdateOrCreate['link'] = "storage/" . $thumb;
                    $this->imageService->updateOrCreateSubThumb($dataUpdateOrCreate);
                }
            }
        }


        return redirect("product/list")->with('statusSuccess', "Bạn đã cập nhật hình ảnh tên '$productName' thành công!");
    }

    public function delete($id)
    {
        $product = Product::withTrashed()->where("id", $id)->first();
        if ($product) {
            $product_id = $product->id;
            $name = $product->name;
        }

        if (empty($product->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->productService->update($id, $dataUpdate);
            $this->productService->delete($id);

            return redirect("product/list")->with("status", "Bạn đã xoá tạm thời sản phẩm tên {$name} thành công!");
        } else {
            $product->forceDelete();
            return redirect("product/list")->with("status", "Bạn đã xoá vĩnh viễn sản phẩm tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->where("id", $id)->first();
        $product->restore();
        $name = $product->name;

        if (empty($product->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->productService->update($id, $dataUpdate);
        }
        return redirect("product/list")->with("status", "Bạn đã khôi phục sản phẩm tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->productService->action($requests);
    }
}
