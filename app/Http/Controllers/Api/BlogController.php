<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Repository\Eloquent\BlogRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;

class BlogController extends Controller
{
    protected $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getUserBlogs(Request $request)
    {
        try {
            $userId = auth()->id();
            $blogs = $this->blogRepository->getUserBlogs($userId);

            return response()->json(['blogs' => $blogs, 'code' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function createBlog(BlogStoreRequest $blogStoreRequest)
    {
        try {
            $blogData = $blogStoreRequest->validated();

            if ($this->blogRepository->createBlog($blogData)) {
                return response()->json(['message' => 'Blog created successfully!', 'code' => 200]);
            } else {
                return response()->json(['error' => 'Failed to create blog. Please try again.'], 500);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found: ' . $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function updateBlog(BlogUpdateRequest $blogUpdateRequest)
    {
        try {
            $blogData = $blogUpdateRequest->validated();

            $result = $this->blogRepository->updateBlog($blogData);

            if ($result) {
                return response()->json(['message' => 'Blog updated successfully', 'blog' => $result], 200);
            } else {
                return response()->json(['message' => 'Failed to update blog'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
