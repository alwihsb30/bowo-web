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

    public function getPendingWithDetails()
    {
        $sql = "SELECT d.*, u.name AS user_name, u.username AS user_username,
                       w.name AS waste_name, w.category AS waste_category
                FROM deposits d
                JOIN users u ON d.user_id = u.id
                JOIN waste_types w ON d.waste_type_id = w.id
                WHERE d.status = 'menunggu'
                ORDER BY d.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllWithDetails()
    {
        $sql = "SELECT d.*, u.name AS user_name, u.username AS user_username,
                       w.name AS waste_name, w.category AS waste_category
                FROM deposits d
                JOIN users u ON d.user_id = u.id
                JOIN waste_types w ON d.waste_type_id = w.id
                ORDER BY d.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalVerifiedWeightByUser($userId)
    {
        $sql = "SELECT IFNULL(SUM(weight), 0)
                FROM deposits
                WHERE user_id = :user_id AND status = 'diverifikasi'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('user_id' => (int)$userId));
        return (float)$stmt->fetchColumn();
    }

    public function countByUser($userId)
    {
        $sql  = "SELECT COUNT(*) FROM deposits WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('user_id' => (int)$userId));
        return (int)$stmt->fetchColumn();
    }

    public function countPendingByUser($userId)
    {
        $sql  = "SELECT COUNT(*) FROM deposits WHERE user_id = :user_id AND status = 'menunggu'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('user_id' => (int)$userId));
        return (int)$stmt->fetchColumn();
    }

    public function getTotalVerifiedWeightAll()
    {
        $sql  = "SELECT IFNULL(SUM(weight), 0) FROM deposits WHERE status = 'diverifikasi'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    public function getTotalVerifiedValueAll()
    {
        $sql  = "SELECT IFNULL(SUM(total_value), 0) FROM deposits WHERE status = 'diverifikasi'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    public function countByStatus($status)
    {
        $sql  = "SELECT COUNT(*) FROM deposits WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('status' => $status));
        return (int)$stmt->fetchColumn();
    }
}
