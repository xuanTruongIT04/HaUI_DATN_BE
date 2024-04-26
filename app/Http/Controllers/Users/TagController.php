<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\TagService;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function listPopular()
    {
        try {
            $tagData = $this->tagService->listPopular();

            return $this->sendSuccessResponse($tagData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}