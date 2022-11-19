<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function create()
    {
        // Get the post data
        $modelLogin = new UserModel();
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");

        $cekUser = $modelLogin->ceklogin($username);
        if (count($cekUser->getResultArray()) > 0) {
            $row = $cekUser->getRowArray();
            $pass_hash = $row['password'];

            if ($password == $pass_hash) {

                $output = [
                    'status' => 200,
                    'error' => 200,
                    'messages' => 'Login Successful',
                    'username' => $username,
                ];

                return $this->respond($output, 200);
            } else {
                return $this->failNotFound("Maaf Username atau Password anda salah");
            }
        } else {
            return $this->failNotFound("Maaf Username atau Password anda salah");
        }
    }
}