<?php
// Model untuk mengelola data pengguna (admin dan nasabah) pada tabel users

class User extends Model
{
    public function findById($id)
    {
        $sql  = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('id' => (int)$id));
        return $stmt->fetch();
    }

    public function findByIdentity($identity)
    {
        $sql = "SELECT * FROM users
                WHERE (username = :username OR email = :email)
                  AND status = 'active'
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            'username' => $identity,
            'email'    => $identity
        ));
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $sql  = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('username' => $username));
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $sql  = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('email' => $email));
        return $stmt->fetch();
    }

    public function registerNasabah($data)
    {
        $sql = "INSERT INTO users (name, username, email, phone, address, password, role, balance, points, status)
                VALUES (:name, :username, :email, :phone, :address, :password, 'nasabah', 0, 0, 'active')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'address'  => $data['address'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ));
        return (int)$this->db->lastInsertId();
    }
}