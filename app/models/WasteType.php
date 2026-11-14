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

    public function createWasteType($data)
    {
        $sql = "INSERT INTO waste_types (name, category, price_per_kg, description, status)
                VALUES (:name, :category, :price_per_kg, :description, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(
            'name'         => $data['name'],
            'category'     => $data['category'],
            'price_per_kg' => (float)$data['price_per_kg'],
            'description'  => $data['description'],
            'status'       => $data['status']
        ));
    }

    public function updateWasteType($id, $data)
    {
        $sql = "UPDATE waste_types
                SET name = :name,
                    category = :category,
                    price_per_kg = :price_per_kg,
                    description = :description,
                    status = :status
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(
            'name'         => $data['name'],
            'category'     => $data['category'],
            'price_per_kg' => (float)$data['price_per_kg'],
            'description'  => $data['description'],
            'status'       => $data['status'],
            'id'           => (int)$id
        ));
    }

    public function deleteById($id)
    {
        $sql  = "DELETE FROM waste_types WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array('id' => (int)$id));
    }
}