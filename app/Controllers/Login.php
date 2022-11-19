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

            if (password_verify($password, $pass_hash)) {
                $issuedate_claim = time();
                $expired_time = $issuedate_claim + 3600;

                $token = [
                    'iat' => $issuedate_claim,
                    'exp' => $expired_time
                ];

                $token = JWT::encode($token, getenv("TOKEN_KEY"), 'HS256');
                $output = [
                    'status' => 200,
                    'error' => 200,
                    'messages' => 'Login Successful',
                    'token' => $token,
                    'username' => $username,
                    'email' => $row['useremail'],
                    'noTelepon' => $row['noTelepon']
                ];

                return $this->respond($output, 200);
            } else {
                return $this->failNotFound("Maaf Username atau Password anda salah");
            }
        } else {
            $row = $cekUser->getRowArray();
            $pass_hash = $row['password'];
            $output = [
                'messages' => $cekUser->getResultArray(),
                'status' => 200,
                'error' => 200,
                'noTelepon' => $row['username'],
                'noTelepon' => $row['password'],

            ];
            return $this->respond($output, 200);
        }
    }
}