<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends ResourceController
{
    public function login()
    {
        // Get the post data
        // $modelLogin = new UserModel();
        $model = new UserModel();
        $data = json_decode(trim(file_get_contents('php://input')), true) ?? $this->request->getPost();
        $user = $model->where("username", $data['username'])->first();
        if (!$user) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => "Username tidak ditemukan"
            ];
            return $this->respond($response, 404);
        }
        if (!password_verify($data['password'], $user['password'])) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => "Password Salah"
            ];
            return $this->respond($response, 404);
        }
        $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Berhasil Login",
            'totaldata' => 1,
            'data' => $user,
        ];
        return $this->respond($response, 201);
        // $username = $this->request->getPost("username");
        // $password = $this->request->getPost("password");

        // $cekUser = $modelLogin->ceklogin($username);
        // if (count($cekUser->getResultArray()) > 0) {
        //     $row = $cekUser->getRowArray();
        //     $pass_hash = $row['password'];

        //     if ($password == $pass_hash) {

        //         $output = [
        //             'status' => 200,
        //             'error' => 200,
        //             'messages' => 'Login Successful',
        //             'username' => $username,
        //         ];

        //         return $this->respond($output, 200);
        //     } else {
        //         return $this->failNotFound("Maaf Username atau Password anda salah");
        //     }
        // } else {
        //     return $this->failNotFound("Maaf Username atau Password anda salah");
        // }
    }

    public function register()
    {
        $model = new UserModel();
        $data = json_decode(trim(file_get_contents('php://input')), true) ?? $this->request->getPost();
        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);

        if (!$model->save($data)) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $model->errors(),
            ];
            return $this->respond($response, 404);
        }
        $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Berhasil Register",
            'data' => $data
        ];
        return $this->respond($response, 201);
    }
}