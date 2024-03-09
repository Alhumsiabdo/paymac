<?php

namespace App\Repository\Eloquent;

use App\Models\Blog;
use App\Repository\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BlogRepository implements BlogRepositoryInterface
{

    public function getUserBlogs($userId)
    {
        try {
            return Blog::where('user_id', $userId)->get();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("User with ID {$userId} not found.");
        } catch (\Exception $e) {

            throw $e;
        }
    }

    public function createBlog(array $blogData)
    {
        try {
            $title = $blogData['title'];
            $content = $blogData['content'];
            $excerpt = $blogData['excerpt'];
            $user_id = auth()->id();

            $blog = new Blog;
            $blog->title = $title;
            $blog->content = $content;
            $blog->excerpt = $excerpt;
            $blog->user_id = $user_id;

            $blog->save();

        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateBlog(array $blogData)
    {
        $blogId = $blogData['id'];
        dd($blogId);

        try {
            $blog = Blog::findOrFail($blogId);
            dd($blog);

            $blog->title = $blogData['title'];
            $blog->content = $blogData['content'];
            $blog->excerpt = $blogData['excerpt'];
            $blog->user_id = auth()->id();

            $blog->save();

            cache::forget('blog_' . $blogId);

            return $blog;
        } catch (\Exception $e) {
            Log::error('Error updating blog: ' . $e->getMessage());
            return false;
        }
    }

}