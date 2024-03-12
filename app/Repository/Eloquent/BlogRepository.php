<?php

namespace App\Repository\Eloquent;

use App\Models\Blog;
use App\Repository\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

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
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateBlog(array $data)
    {

        $blogId = $data['id'];

        $blog = Blog::findOrFail($blogId);

        $fillableColumns = ['title', 'content', 'excerpt'];

        $currentValues = $blog->only($fillableColumns);

        $updatesProvided = false;
        foreach ($fillableColumns as $column) {
            if (array_key_exists($column, $data) && $data[$column] !== null) {
                $updatesProvided = true;
                break;
            }
        }

        if (!$updatesProvided) {
            return [
                'blog' => $currentValues,
                'message' => 'No updates provided. Retrieved the current blog data.',
                'code' => 200
            ];
        }

        foreach ($fillableColumns as $column) {
            if (array_key_exists($column, $data) && $data[$column] !== null) {
                $blog->{$column} = $data[$column];
            }
        }

        $blog->save();
        return [
            'blog' => $blog->refresh()->only($fillableColumns),
            'message' => 'Blog updated successfully',
            'code' => 200
        ];
    }

    public function deleteBlog($blogId, $userId)
    {
        try {
            $blog = Blog::findOrFail($blogId);

            if ($blog->user_id !== $userId) {
                return false;
            } else {
                $blog->delete();
                return true;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getAllPaginated($page = 1, $perPage = 12, $userId = null, $searchQuery = null)
    {
        try {
            $query = Blog::query();

            if ($userId !== null) {
                $query->where('user_id', $userId);
            }

            if ($searchQuery !== null) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('title', 'like', "%$searchQuery%")
                        ->orWhere('content', 'like', "%$searchQuery%");
                });
            }

            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (QueryException $e) {
            throw $e;
        }
    }
}