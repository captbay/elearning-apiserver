<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelUser = new UserModel();
        $data = $modelUser->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        return $this->respond($response, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $model = new UserModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $modelUser = new UserModel();
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        $email = $this->request->getPost("email");
        $tglLahir = $this->request->getPost("tglLahir");
        $noTelp = $this->request->getPost("noTelp");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'username' => [
                'rules' => 'is_unique[user.username]',
                'label' => 'Username User',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);
        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("username"),
            ];
            return $this->respond($response, 404);
        } else {
            $modelUser->insert([
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'tglLahir' => $tglLahir,
                'noTelp' => $noTelp,
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];
            return $this->respond($response, 201);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $modelUser = new UserModel();
        $data = [
            'username' => $this->request->getVar("username"),
            'password' => $this->request->getVar("password"),
            'email' => $this->request->getVar("email"),
            'tglLahir' => $this->request->getVar("tglLahir"),
            'noTelp' => $this->request->getVar("noTelp"),
        ];
        $data = $this->request->getRawInput();
        $modelUser->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan id $id berhasil dibaharukan"
        ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }

}