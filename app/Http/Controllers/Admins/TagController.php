<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admins\Tag\EditTagRequest;
use App\Http\Requests\Admins\Tag\StoreTagRequest;

use App\Models\Tag;
use App\Services\TagService;
use App\Services\ImageService;

class TagController extends Controller
{
    //
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    function list(Request $request)
    {
        $tags = $this->tagService->all();
        $countTags = $tags->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->tagService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $tags = $this->tagService->searchTags($keyWord, 20, $statusData, $where);
        $tags->withQueryString();
        
        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countTagsSearch = $tags->total();
        $cntTagActive = $this->tagService->countTags();
        $cntTagLicensed = $this->tagService->countTags("without", $listCondition[0]);
        $cntTagPending = $this->tagService->countTags("without", $listCondition[1]);
        $cntTagTrashed = $this->tagService->countTags("only");
        // Merge to array count status
        $countTagStatus = [$cntTagActive, $cntTagLicensed, $cntTagPending, $cntTagTrashed];

        return view("tag.list", compact('tags', "countTagStatus", "listAct", "countTags", "countTagsSearch"));
    }

    public function add()
    {
        return view('tag.add');
    }

    public function store(StoreTagRequest $request)
    {
        $dataCreate = $request->validated();
        $name = $request->input("name");

        $this->tagService->create($dataCreate);
        return redirect("tag/list")->with('statusSuccess', "Bạn đã thêm tag tên '$name' thành công!");

    }

    public function edit($id)
    {
        $tag = $this->tagService->find($id);

        return view('tag.edit', compact("tag"));
    }

    public function update(EditTagRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        $status = $dataUpdate['status'];
        // Update super tag
        $this->tagService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update tag other
        $tagName = $this->tagService->find($id)->name;
        if ($status == $listCondition[2]) {
            $this->tagService->delete($id);
        }
        return redirect("tag/list")->with('statusSuccess', "Bạn đã cập nhật thông tin tag tên '$tagName' thành công!");
    }

    public function delete($id)
    {
        $tag = Tag::withTrashed()->where("id", $id)->first();
        $tag_id = $tag->id;
        $name = $tag->name;

        if (empty($tag->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->tagService->update($id, $dataUpdate);
            $this->tagService->delete($id);

            return redirect("tag/list")->with("status", "Bạn đã xoá tạm thời tag tên {$name} thành công!");
        } else {
            $tag->forceDelete();
            return redirect("tag/list")->with("status", "Bạn đã xoá vĩnh viễn tag tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        // dd(Auth::id(), $id);
        $tag = Tag::onlyTrashed()->where("id", $id)->first();
        $tag->restore();
        $name = $tag->name;

        if (empty($tag->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->tagService->update($id, $dataUpdate);
        }
        return redirect("tag/list")->with("status", "Bạn đã khôi phục tag tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->tagService->action($requests);
    }
}