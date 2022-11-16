<?php

namespace App\Models;

use CodeIgniter\Model;

class TodolistModel extends Model
{
    protected $table            = 'todolists';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['tglDibuat', 'tglDeadline', 'judul', 'pesan', 'status'];
}