<?php
// Class induk Model sebagai penyedia koneksi database PDO bagi seluruh model

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }
}
