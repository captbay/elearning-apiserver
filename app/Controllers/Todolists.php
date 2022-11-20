<?php

namespace App\Controllers;

use App\Models\TodolistModel;
use CodeIgniter\RESTful\ResourceController;

class Todolists extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelTodolist = new TodolistModel();
        $data = $modelTodolist->findAll();
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
        $model = new TodolistModel();
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
        $modelTodolist = new TodolistModel();
        $tglDibuat = $this->request->getPost("tglDibuat");
        $tglDeadline = $this->request->getPost("tglDeadline");
        $judul = $this->request->getPost("judul");
        $pesan = $this->request->getPost("pesan");
        $status = $this->request->getPost("status");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'judul' => [
                'rules' => 'is_unique[todolists.judul]',
                'label' => 'Judul Todolist',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);
        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("judul"),
            ];
            return $this->respond($response, 404);
        } else {
            $modelTodolist->insert([
                'tglDibuat' => $tglDibuat,
                'tglDeadline' => $tglDeadline,
                'judul' => $judul,
                'pesan' => $pesan,
                'status' => $status,
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
        $modelTodolist = new TodolistModel();
        $data = [
            'tglDibuat' => $this->request->getVar("tglDibuat"),
            'tglDeadline' => $this->request->getVar("tglDeadline"),
            'judul' => $this->request->getVar("judul"),
            'pesan' => $this->request->getVar("pesan"),
            'status' => $this->request->getVar("status"),
        ];
        $data = $this->request->getRawInput();
        $modelTodolist->update($id, $data);
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
        $modelTodolist = new TodolistModel();
        $cekData = $modelTodolist->find($id);
        if ($cekData) {
            $modelTodolist->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}