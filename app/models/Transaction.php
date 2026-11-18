<?php
// Model untuk mengelola riwayat mutasi saldo dan verifikasi setoran oleh admin

class Transaction extends Model
{
    public function getByUserWithDetails($userId)
    {
        $sql = "SELECT t.*, d.weight AS deposit_weight, w.name AS waste_name
                FROM transactions t
                LEFT JOIN deposits d ON t.deposit_id = d.id
                LEFT JOIN waste_types w ON d.waste_type_id = w.id
                WHERE t.user_id = :user_id
                ORDER BY t.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('user_id' => (int)$userId));
        return $stmt->fetchAll();
    }

    public function getTotalAmountByUserAndType($userId, $type)
    {
        $sql = "SELECT IFNULL(SUM(amount), 0)
                FROM transactions
                WHERE user_id = :user_id AND type = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            'user_id' => (int)$userId,
            'type'    => $type
        ));
        return (float)$stmt->fetchColumn();
    }
}