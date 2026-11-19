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

    public function processDepositVerification($depositId, $adminId, $status)
    {
        $finalStatus = ($status === 'diverifikasi') ? 'diverifikasi' : 'ditolak';

        $sqlGet  = "SELECT * FROM deposits WHERE id = :id LIMIT 1";
        $stmtGet = $this->db->prepare($sqlGet);
        $stmtGet->execute(array('id' => (int)$depositId));
        $deposit = $stmtGet->fetch();

        if (!$deposit || $deposit['status'] !== 'menunggu') {
            return false;
        }

        $sqlUpdate = "UPDATE deposits
                      SET status = :status, verified_by = :verified_by, verified_at = :verified_at
                      WHERE id = :id";
        $stmtUpdate = $this->db->prepare($sqlUpdate);
        $stmtUpdate->execute(array(
            'status'      => $finalStatus,
            'verified_by' => (int)$adminId,
            'verified_at' => date('Y-m-d H:i:s'),
            'id'          => (int)$depositId
        ));

        if ($finalStatus !== 'diverifikasi') {
            return true;
        }

        $sqlUser = "UPDATE users
                    SET balance = balance + :balance, points = points + :points
                    WHERE id = :user_id";
        $stmtUser = $this->db->prepare($sqlUser);
        $stmtUser->execute(array(
            'balance' => (float)$deposit['total_value'],
            'points'  => (int)$deposit['points'],
            'user_id' => (int)$deposit['user_id']
        ));

        $sqlTx = "INSERT INTO transactions (user_id, deposit_id, type, amount, description)
                  VALUES (:user_id, :deposit_id, 'setoran_masuk', :amount, :description)";
        $stmtTx = $this->db->prepare($sqlTx);
        $stmtTx->execute(array(
            'user_id'     => (int)$deposit['user_id'],
            'deposit_id'  => (int)$deposit['id'],
            'amount'      => (float)$deposit['total_value'],
            'description' => 'Hasil setoran sampah ' . $deposit['weight'] . ' kg'
        ));

        return true;
    }
}
