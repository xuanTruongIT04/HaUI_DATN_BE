<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Post\EditPostRequest;
use App\Http\Requests\Admins\Post\StorePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    function list(Request $request)
    {
        $posts = $this->postService->all();
        $countPosts = $posts->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->postService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $posts = $this->postService->searchPosts($keyWord, 20, $statusData, $where);
        $posts->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countPostsSearch = $posts->total();
        $cntPostActive = $this->postService->countPosts();
        $cntPostLicensed = $this->postService->countPosts("without", $listCondition[0]);
        $cntPostPending = $this->postService->countPosts("without", $listCondition[1]);
        $cntPostTrashed = $this->postService->countPosts("only");
        // Merge to array count status
        $countPostStatus = [$cntPostActive, $cntPostLicensed, $cntPostPending, $cntPostTrashed];

        return view("post.list", compact('posts', "countPostStatus", "listAct", "countPosts", "countPostsSearch"));
    }

    public function add()
    {
        return view('post.add');
    }

    public function store(StorePostRequest $request)
    {
        $dataCreate = $request->validated();

        //Validate thumb post
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

        $title = $request->input("title");

        $this->postService->create($dataCreate);
        return redirect("post/list")->with('statusSuccess', "Bạn đã thêm bài viết tiêu đề '$title' thành công!");

    }

    public function edit($id)
    {
        $post = $this->postService->find($id);

        return view('post.edit', compact("post"));
    }

    public function update(EditPostRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        //Validate thumb post
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
        // Update super post
        $this->postService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update post other
        $postName = $this->postService->find($id)->title;
        if ($status == $listCondition[2]) {
            // dd($status);
            $this->postService->delete($id);
        }
        return redirect("post/list")->with('statusSuccess', "Bạn đã cập nhật thông tin bài viết tiêu đề '$postName' thành công!");
    }

    public function delete($id)
    {
        $post = Post::withTrashed()->where("id", $id)->first();
        $post_id = $post->id;
        $title = $post->title;

        if (empty($post->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->postService->update($id, $dataUpdate);
            $this->postService->delete($id);

            return redirect("post/list")->with("status", "Bạn đã xoá tạm thời bài viết tiêu đề {$title} thành công!");
        } else {
            $post->forceDelete();
            return redirect("post/list")->with("status", "Bạn đã xoá vĩnh viễn bài viết tiêu đề {$title} thành công!");
        }
    }

    public function restore($id)
    {
        // dd(Auth::id(), $id);
        $post = Post::onlyTrashed()->where("id", $id)->first();
        $post->restore();
        $title = $post->title;

        if (empty($post->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->postService->update($id, $dataUpdate);
        }
        return redirect("post/list")->with("status", "Bạn đã khôi phục bài viết tiêu đề '$title' thành công");
    }

    public function action(Request $requests)
    {
        return $this->postService->action($requests);
    }
}