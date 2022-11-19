<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'email', 'tglLahir', 'noTelp'];

    public function ceklogin($username){
        $query = $this->table($this->table)->getWhere(['username' => $username]);
        return $query;
    }
}