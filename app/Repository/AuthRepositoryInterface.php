<?php 

namespace App\Repository;

Interface AuthRepositoryInterface {

    public function register(array $userData);
    public function login(array $credentials);
    public function logout();

}