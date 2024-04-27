<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admins\Slide\EditSlideRequest;
use App\Http\Requests\Admins\Slide\StoreSlideRequest;

use App\Models\Slide;
use App\Services\SlideService;
use App\Services\ImageService;

class SlideController extends Controller
{
    //
    protected $slideService;

    public function __construct(SlideService $slideService)
    {
        $this->slideService = $slideService;
    }

    function list(Request $request)
    {
        $slides = $this->slideService->all();
        $countSlides = $slides->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->slideService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $slides = $this->slideService->searchSlides($keyWord, 20, $statusData, $where);
        $slides->withQueryString();
        
        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countSlidesSearch = $slides->total();
        $cntSlideActive = $this->slideService->countSlides();
        $cntSlideLicensed = $this->slideService->countSlides("without", $listCondition[0]);
        $cntSlidePending = $this->slideService->countSlides("without", $listCondition[1]);
        $cntSlideTrashed = $this->slideService->countSlides("only");
        // Merge to array count status
        $countSlideStatus = [$cntSlideActive, $cntSlideLicensed, $cntSlidePending, $cntSlideTrashed];

        return view("slide.list", compact('slides', "countSlideStatus", "listAct", "countSlides", "countSlidesSearch"));
    }

    public function add()
    {
        return view('slide.add');
    }

    public function store(StoreSlideRequest $request)
    {
        $dataCreate = $request->validated();
        //Validate thumb slide
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
            $dataCreate['link'] = "storage/" . $thumb;
        }
        $name = $request->input("name");

        $this->slideService->create($dataCreate);
        return redirect("slide/list")->with('statusSuccess', "Bạn đã thêm slide tên '$name' thành công!");

    }

    public function edit($id)
    {
        $slide = $this->slideService->find($id);

        return view('slide.edit', compact("slide"));
    }

    public function update(EditSlideRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        //Validate thumb slide
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
        // Update super slide
        $this->slideService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update slide other
        $slideName = $this->slideService->find($id)->name;
        if ($status == $listCondition[2]) {
            $this->slideService->delete($id);
        }
        return redirect("slide/list")->with('statusSuccess', "Bạn đã cập nhật thông tin slide tên '$slideName' thành công!");
    }

    public function delete($id)
    {
        $slide = Slide::withTrashed()->where("id", $id)->first();
        $slide_id = $slide->id;
        $name = $slide->name;

        if (empty($slide->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->slideService->update($id, $dataUpdate);
            $this->slideService->delete($id);

            return redirect("slide/list")->with("status", "Bạn đã xoá tạm thời slide tên {$name} thành công!");
        } else {
            $slide->forceDelete();
            return redirect("slide/list")->with("status", "Bạn đã xoá vĩnh viễn slide tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        // dd(Auth::id(), $id);
        $slide = Slide::onlyTrashed()->where("id", $id)->first();
        $slide->restore();
        $name = $slide->name;

        if (empty($slide->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->slideService->update($id, $dataUpdate);
        }
        return redirect("slide/list")->with("status", "Bạn đã khôi phục slide tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->slideService->action($requests);
    }
}