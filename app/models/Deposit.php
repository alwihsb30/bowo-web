<?php
// Model untuk mengelola data pengajuan setoran sampah pada tabel deposits

class Deposit extends Model
{
    public function createDeposit($data)
    {
        $sql = "INSERT INTO deposits
                (user_id, waste_type_id, weight, price_per_kg, total_value, points, deposit_date, notes, photo_proof, status)
                VALUES
                (:user_id, :waste_type_id, :weight, :price_per_kg, :total_value, :points, :deposit_date, :notes, :photo_proof, 'menunggu')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(
            'user_id'       => (int)$data['user_id'],
            'waste_type_id' => (int)$data['waste_type_id'],
            'weight'        => (float)$data['weight'],
            'price_per_kg'  => (float)$data['price_per_kg'],
            'total_value'   => (float)$data['total_value'],
            'points'        => (int)$data['points'],
            'deposit_date'  => $data['deposit_date'],
            'notes'         => $data['notes'],
            'photo_proof'   => $data['photo_proof']
        ));
    }

    public function getByUserWithWaste($userId)
    {
        $sql = "SELECT d.*, w.name AS waste_name, w.category AS waste_category
                FROM deposits d
                JOIN waste_types w ON d.waste_type_id = w.id
                WHERE d.user_id = :user_id
                ORDER BY d.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('user_id' => (int)$userId));
        return $stmt->fetchAll();
    }
}