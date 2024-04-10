<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\Post\FilterRequest;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use App\Models\PostColor;

class PostController extends Controller
{
    //
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getAllLatest()
    {
        try {
            $postData = $this->postService->getAllLatest();
            return $this->sendSuccessResponse($postData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function getAllLicensed()
    {
        try {
            $postData = $this->postService->getAllLicensed();
            return $this->sendSuccessResponse($postData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function detail($idPost)
    {
        try {
            $postData = $this->postService->getDetailPost($idPost);
            return $this->sendSuccessResponse($postData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

}