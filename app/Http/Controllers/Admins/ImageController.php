<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admins\Image\EditImageRequest;
use App\Http\Requests\Admins\Image\StoreImageRequest;

use App\Models\Image;
use App\Services\ImageService;

class ImageController extends Controller
{
    //
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    function list(Request $request)
    {
        $images = $this->imageService->all();
        $countImages = $images->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }
        // Get constraint action
        $constraintAction = $this->imageService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $images = $this->imageService->searchImages($keyWord, 20, $statusData, $where);
        $images->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countImagesSearch = $images->total();
        $cntImageActive = $this->imageService->countImages();
        $cntImageLicensed = $this->imageService->countImages("without", $listCondition[0]);
        $cntImagePending = $this->imageService->countImages("without", $listCondition[1]);
        $cntImageTrashed = $this->imageService->countImages("only");
        // Merge to array count status
        $countImageStatus = [$cntImageActive, $cntImageLicensed, $cntImagePending, $cntImageTrashed];

        return view("image.list", compact('images', "countImageStatus", "listAct", "countImages", "countImagesSearch"));
    }

    public function add($idProduct = "")
    {
        $product = Product::find($idProduct);
        $productName = $product ? $product->name : "";
        return view('image.add', compact("idProduct", "productName"));
    }

    public function store(StoreImageRequest $request, $productId = "")
    {
        $dataCreate = $request->validated();
        if ($productId) {
            $dataCreate['product_id'] = $productId;
        } else {
            $productId = $dataCreate['product_id'];
        }
        $product = Product::find($productId);
        $productName = $product ? $product->name : '';

        // Validate thumb image
        if ($request->hasFile("thumb")) {
            $thumb = uploadFileHelper($request, 'thumb');
        } else {
            if ($request->thumb && $request->thumb != 'null') {
                unset($thumb);
            } else {
                $thumb = '';
            }
        }
        if (!empty($thumb)) {
            $dataCreate['link'] = "storage/" . $thumb;
        }

        $dataCreate['link'] = $thumb;
        $dataCreate['level'] = Constant::LEVEL_IMAGE[0];
        $dataCreate['description'] = "";
        $this->imageService->create($dataCreate);

        $dataCreate['level'] = Constant::LEVEL_IMAGE[1];

        if ($request->hasFile("list_thumb")) {
            $listImages = $request->file("list_thumb");
            foreach ($listImages as $image) {
                $listThumb = uploadMultiFileHelper($request, 'list_thumb');
            }

            if (!empty($listThumb)) {
                foreach ($listThumb as $thumb) {
                    $dataCreate['link'] = "storage/" . $thumb;
                    $this->imageService->updateOrCreateSubThumb($dataCreate);
                }
            }
        }

        return redirect("image/list")->with('statusSuccess', "Bạn đã thêm hình ảnh tên '$productName' thành công!");
    }
    public function edit($id)
    {
        $image = $this->imageService->find($id);

        return view('image.edit', compact("image"));
    }

    public function update(EditImageRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        //Validate thumb image
        if ($request->hasFile('thumb')) {
            $thumb = uploadFileHelper($request, 'thumb');
        } else {
            if ($request->thumb && $request->thumb != 'null') {
                unset($thumb);
            } else {
                $thumb = '';
            }
        }
        if (!empty($thumb)) {
            $dataUpdate['link'] = "storage/" . $thumb;
        }
        $status = $dataUpdate['status'];
        // Update super image
        $this->imageService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update image other
        $imageName = $this->imageService->find($id)->name;
        if ($status == $listCondition[2]) {
            $this->imageService->delete($id);
        }
        return redirect("image/list")->with('statusSuccess', "Bạn đã cập nhật thông tin hình ảnh tên '$imageName' thành công!");
    }

    public function delete($id)
    {
        $image = Image::withTrashed()->where("id", $id)->first();
        $name = $image->name;

        if (empty($image->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->imageService->update($id, $dataUpdate);
            $this->imageService->delete($id);

            return redirect("image/list")->with("status", "Bạn đã xoá tạm thời hình ảnh tên {$name} thành công!");
        } else {
            $image->forceDelete();
            return redirect("image/list")->with("status", "Bạn đã xoá vĩnh viễn hình ảnh tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $image = Image::onlyTrashed()->where("id", $id)->first();
        $image->restore();
        $name = $image->name;

        if (empty($image->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->imageService->update($id, $dataUpdate);
        }
        return redirect("image/list")->with("status", "Bạn đã khôi phục hình ảnh tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->imageService->action($requests);
    }
}
