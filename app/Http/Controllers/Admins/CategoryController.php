<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Category\EditCategoryRequest;
use App\Http\Requests\Admins\Category\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    function list(Request $request)
    {
        $categories = $this->categoryService->all();
        $countCategories = $categories->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->categoryService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $categories = $this->categoryService->searchCategories($keyWord, 20, $statusData, $where);
        $categories->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countCategoriesSearch = $categories->total();
        $cntCategoryActive = $this->categoryService->countCategories();
        $cntCategoryLicensed = $this->categoryService->countCategories("without", $listCondition[0]);
        $cntCategoryPending = $this->categoryService->countCategories("without", $listCondition[1]);
        $cntCategoryTrashed = $this->categoryService->countCategories("only");
        // Merge to array count status
        $countCategoryStatus = [$cntCategoryActive, $cntCategoryLicensed, $cntCategoryPending, $cntCategoryTrashed];

        return view("category.list", compact('categories', "countCategoryStatus", "listAct", "countCategories", "countCategoriesSearch"));
    }

    public function add()
    {
        $categories = $this->categoryService->getAllAscLevel();
        return view('category.add', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $dataCreate = $request->validated();

        $title = $request->input("title");

        $this->categoryService->create($dataCreate);
        return redirect("category/list")->with('statusSuccess', "Bạn đã thêm danh mục tiêu đề '$title' thành công!");

    }

    public function edit($id)
    {
        $categories = $this->categoryService->getAllAscLevel();
        $category = $this->categoryService->find($id);
        //Remove this $category from the $categories object array
        foreach ($categories as $key => $value) {
            if ($value->id == $category->id) {
                unset($categories[$key]);
                break;
            }
        }
        return view('category.edit', compact("categories", "category"));
    }

    public function update(EditCategoryRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        $status = $dataUpdate['status'];
        // Update super category
        $this->categoryService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update category other
        $categoryTitle = $this->categoryService->find($id)->title;
        if ($status == $listCondition[2]) {
            $this->categoryService->delete($id);
        }
        return redirect("category/list")->with('statusSuccess', "Bạn đã cập nhật thông tin danh mục tiêu đề '$categoryTitle' thành công!");
    }

    public function delete($id)
    {
        $category = Category::withTrashed()->where("id", $id)->first();
        $category_id = $category->id;
        $title = $category->title;

        if (empty($category->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->categoryService->update($id, $dataUpdate);
            $this->categoryService->delete($id);

            return redirect("category/list")->with("status", "Bạn đã xoá tạm thời danh mục tiêu đề {$title} thành công!");
        } else {
            $category->forceDelete();
            return redirect("category/list")->with("status", "Bạn đã xoá vĩnh viễn danh mục tiêu đề {$title} thành công!");
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->where("id", $id)->first();
        $category->restore();
        $title = $category->title;

        if (empty($category->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->categoryService->update($id, $dataUpdate);
        }
        return redirect("category/list")->with("status", "Bạn đã khôi phục danh mục tiêu đề '$title' thành công");
    }

    public function action(Request $requests)
    {
        return $this->categoryService->action($requests);
    }
}