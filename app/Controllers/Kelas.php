<?php

namespace App\Controllers;

use App\Models\KelasModel;
use CodeIgniter\RESTful\ResourceController;

class Kelas extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelKelas = new KelasModel();
        $data = $modelKelas->findAll();
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
        $model = new KelasModel();
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
        $modelKelas = new KelasModel();
        $mataPelajaran = $this->request->getPost("mataPelajaran");
        $sesiKelas = $this->request->getPost("sesiKelas");
        $tahunAjaran = $this->request->getPost("tahunAjaran");
        $guruPengajar = $this->request->getPost("guruPengajar");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'mataPelajaran' => [
                'rules' => 'is_unique[kelas.mataPelajaran]',
                'label' => 'Judul mataPelajaran',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);
        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("mataPelajaran"),
            ];
            return $this->respond($response, 404);
        } else {
            $modelKelas->insert([
                'mataPelajaran' => $mataPelajaran,
                'sesiKelas' => $sesiKelas,
                'tahunAjaran' => $tahunAjaran,
                'guruPengajar' => $guruPengajar,
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
        $modelKelas = new KelasModel();
        $data = [
            'mataPelajaran' => $this->request->getVar("mataPelajaran"),
            'sesiKelas' => $this->request->getVar("sesiKelas"),
            'tahunAjaran' => $this->request->getVar("tahunAjaran"),
            'guruPengajar' => $this->request->getVar("guruPengajar"),
        ];
        $data = $this->request->getRawInput();
        $modelKelas->update($id, $data);
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
        $modelKelas = new KelasModel();
        $cekData = $modelKelas->find($id);
        if ($cekData) {
            $modelKelas->delete($id);
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