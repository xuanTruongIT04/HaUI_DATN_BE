<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Color\EditColorRequest;
use App\Http\Requests\Admins\Color\StoreColorRequest;
use App\Models\Color;
use App\Services\ColorService;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    //
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    function list(Request $request)
    {
        $colors = $this->colorService->all();
        $countColors = $colors->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->colorService->constraintAction($request);
        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $colors = $this->colorService->searchColors($keyWord, 20, $statusData, $where);
        $colors->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countColorsSearch = $colors->total();
        $cntColorActive = $this->colorService->countColors();
        $cntColorLicensed = $this->colorService->countColors("without", $listCondition[0]);
        $cntColorPending = $this->colorService->countColors("without", $listCondition[1]);
        $cntColorTrashed = $this->colorService->countColors("only");
        // Merge to array count status
        $countColorStatus = [$cntColorActive, $cntColorLicensed, $cntColorPending, $cntColorTrashed];

        return view("color.list", compact('colors', "countColorStatus", "listAct", "countColors", "countColorsSearch"));
    }

    public function add()
    {
        return view('color.add');
    }

    public function store(StoreColorRequest $request)
    {
        $dataCreate = $request->validated();

        $name = $request->input("name");

        $this->colorService->create($dataCreate);
        return redirect("color/list")->with('statusSuccess', "Bạn đã thêm màu sắc tên '$name' thành công!");

    }

    public function edit($id)
    {
        $color = $this->colorService->find($id);

        return view('color.edit', compact("color"));
    }

    public function update(EditColorRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        $status = $dataUpdate['status'];
        // Update super color
        $this->colorService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update color other
        $colorName = $this->colorService->find($id)->name;
        if ($status == $listCondition[2]) {
            // dd($status);
            $this->colorService->delete($id);
        }
        return redirect("color/list")->with('statusSuccess', "Bạn đã cập nhật thông tin màu sắc tên '$colorName' thành công!");
    }

    public function delete($id)
    {
        $color = Color::withTrashed()->where("id", $id)->first();
        $color_id = $color->id;
        $name = $color->name;

        if (empty($color->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->colorService->update($id, $dataUpdate);
            $this->colorService->delete($id);

            return redirect("color/list")->with("status", "Bạn đã xoá tạm thời màu sắc tên {$name} thành công!");
        } else {
            $color->forceDelete();
            return redirect("color/list")->with("status", "Bạn đã xoá vĩnh viễn màu sắc tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $color = Color::onlyTrashed()->where("id", $id)->first();
        $color->restore();
        $name = $color->name;

        if (empty($color->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->colorService->update($id, $dataUpdate);
        }
        return redirect("color/list")->with("status", "Bạn đã khôi phục màu sắc tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->colorService->action($requests);
    }
}