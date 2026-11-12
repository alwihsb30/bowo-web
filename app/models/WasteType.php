<?php
// Model untuk mengelola data master jenis sampah pada tabel waste_types

class WasteType extends Model
{
    public function getAll()
    {
        $sql  = "SELECT * FROM waste_types ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql  = "SELECT * FROM waste_types WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('id' => (int)$id));
        return $stmt->fetch();
    }

    public function getActive()
    {
        $sql  = "SELECT * FROM waste_types WHERE status = 'active' ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}