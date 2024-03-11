<?php 

namespace App\Repository;

Interface BlogRepositoryInterface {

    public function getUserBlogs($userId);

    public function createBlog(array $blogData);

    public function updateBlog(array $data);

    public function deleteBlog($blogId, $userId);

    public function getAllPaginated($page = 1, $perPage = 12, $userId = null, $searchQuery = null);

}