<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admins\ProductTag\EditProductTagRequest;
use App\Http\Requests\Admins\ProductTag\StoreProductTagRequest;

use App\Services\ProductTagService;

use App\Helpers\Constant;
use App\Models\ProductTag;
use App\Services\ProductService;
use App\Services\TagService;

class ProductTagController extends Controller
{
    protected $tagService, $productTagService, $productService;

    public function __construct(TagService $tagService, ProductService $productService, ProductTagService $productTagService)
    {
        $this->tagService = $tagService;
        $this->productTagService = $productTagService;
        $this->productService = $productService;
    }

    function list(Request $request)
    {
        $productTags = $this->productTagService->all();
        $countTags = $productTags->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->productTagService->constraintAction($request);

        $where = $constraintAction['where'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $productTags = $this->productTagService->searchProductTags($keyWord, 20, $statusData, $where);
        $productTags->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countTagsSearch = $productTags->total();
        $cntTagActive = $this->productTagService->countProductTags();
        $cntTagTrashed = $this->productTagService->countProductTags("only");
        // Merge to array count status
        $countTagStatus = [$cntTagActive, $cntTagTrashed];

        return view("productTag.list", compact('productTags', "countTagStatus", "listAct", "countTags", "countTagsSearch"));
    }

    public function add()
    {
        return view('productTag.add');
    }

    public function store(StoreProductTagRequest $request)
    {
        $dataCheck = $request->validated();
        //Validate thumb productTag
        $productTag = $this->productTagService->checkExists($dataCheck);

        if ($productTag) {
            $tagName = $productTag?->tag?->name;
            $productName = $productTag?->product?->name;
            return redirect("product-tag/list")->with('statusFail', "Bạn đã thêm thẻ '$tagName' cho sản phẩm '$productName' thất bại vì đã tồn tại!");
        } else {
            $productName = $this->productService->find($dataCheck['product_id'])?->name;
            $tagName = $this->tagService->find($dataCheck['tag_id'])?->name;

            $this->productTagService->create($dataCheck);
            return redirect("product-tag/list")->with('statusSuccess', "Bạn đã thêm thẻ '$tagName' cho sản phẩm '$productName' thành công!");
        }
    }

    public function delete($id)
    {
        $productTag = ProductTag::onlyTrashed()->where("id", $id)->first();
        $name = $productTag->tag->name;

        if (empty($productTag->deleted_at)) {
            $this->productTagService->delete($id);

            return redirect("product-tag/list")->with("status", "Bạn đã xoá tạm thời chi tiết thẻ tên {$name} thành công!");
        } else {
            $productTag->forceDelete();
            return redirect("product-tag/list")->with("status", "Bạn đã xoá vĩnh viễn chi tiết thẻ tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $productTag = ProductTag::onlyTrashed()->where("id", $id)->first();
        $productTag->restore();
        $name = $productTag->tag->name;

        return redirect("product-tag/list")->with("status", "Bạn đã khôi phục chi tiết thẻ tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->productTagService->action($requests);
    }
}