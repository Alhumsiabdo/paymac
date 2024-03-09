<?php 

namespace App\Repository;

Interface BlogRepositoryInterface {

    public function getUserBlogs($userId);

    public function createBlog(array $blogData);

    public function updateBlog(array $blogData);

    // public function delete($id);

}