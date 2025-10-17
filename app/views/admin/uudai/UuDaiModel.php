<?php
class UuDaiModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy danh sách tất cả ưu đãi theo cơ sở
     */
    public function findUuDaisByCoSo($maCoSo) {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                WHERE p.ma_co_so = :ma_co_so 
                ORDER BY p.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_co_so', $maCoSo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin ưu đãi theo ID
     */
    public function findUuDaiById($id) {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                WHERE p.id = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách tất cả ưu đãi (không phân biệt cơ sở)
     */
    public function findAllUuDais() {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                ORDER BY p.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm ưu đãi mới
     */
    public function createUuDai($data) {
        $sql = "INSERT INTO promotions 
                (code, name, type, value, start_date, end_date, description, conditions, status, ma_co_so) 
                VALUES 
                (:code, :name, :type, :value, :start_date, :end_date, :description, :conditions, :status, :ma_co_so)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật thông tin ưu đãi
     */
    public function updateUuDai($id, $data) {
        $sql = "UPDATE promotions SET 
                code = :code, 
                name = :name, 
                type = :type, 
                value = :value, 
                start_date = :start_date, 
                end_date = :end_date, 
                description = :description, 
                conditions = :conditions, 
                status = :status,
                ma_co_so = :ma_co_so,
                updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Xóa ưu đãi
     */
    public function deleteUuDai($id) {
        $sql = "DELETE FROM promotions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Kiểm tra mã ưu đãi đã tồn tại chưa (dùng cho thêm mới)
     */
    public function isCodeExists($code) {
        $sql = "SELECT id FROM promotions WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Kiểm tra mã ưu đãi đã tồn tại chưa (dùng cho cập nhật, loại trừ bản ghi hiện tại)
     */
    public function isCodeExistsExceptCurrent($code, $currentId) {
        $sql = "SELECT id FROM promotions WHERE code = :code AND id != :current_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':current_id', $currentId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Lấy thống kê ưu đãi theo cơ sở
     */
    public function getUuDaiStatsByCoSo($maCoSo) {
        $sql = "SELECT status, COUNT(*) as count 
                FROM promotions 
                WHERE ma_co_so = :ma_co_so 
                GROUP BY status";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_co_so', $maCoSo);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats = ['active' => 0, 'inactive' => 0];
        
        foreach ($result as $row) {
            $stats[$row['status']] = (int)$row['count'];
        }
        
        return $stats;
    }

    /**
     * Lấy tổng số ưu đãi theo cơ sở
     */
    public function getTotalUuDaisByCoSo($maCoSo) {
        $sql = "SELECT COUNT(*) as total 
                FROM promotions 
                WHERE ma_co_so = :ma_co_so";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_co_so', $maCoSo);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    /**
     * Lấy ưu đãi đang hoạt động theo cơ sở
     */
    public function findActiveUuDaisByCoSo($maCoSo) {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                WHERE p.ma_co_so = :ma_co_so 
                AND p.status = 'active' 
                AND p.start_date <= CURDATE() 
                AND p.end_date >= CURDATE() 
                ORDER BY p.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_co_so', $maCoSo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy ưu đãi sắp hết hạn (còn 7 ngày)
     */
    public function findExpiringUuDais($maCoSo) {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                WHERE p.ma_co_so = :ma_co_so 
                AND p.status = 'active' 
                AND p.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                ORDER BY p.end_date ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_co_so', $maCoSo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra ưu đãi có tồn tại không
     */
    public function uuDaiExists($id) {
        $sql = "SELECT id FROM promotions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Cập nhật trạng thái ưu đãi
     */
    public function updateUuDaiStatus($id, $status) {
        $sql = "UPDATE promotions SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Lấy ưu đãi theo mã code
     */
    public function findUuDaiByCode($code) {
        $sql = "SELECT p.*, c.TenCoSo 
                FROM promotions p 
                LEFT JOIN coso c ON p.ma_co_so = c.MaCoSo 
                WHERE p.code = :code";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách các cơ sở có ưu đãi
     */
    public function findBranchesWithUuDais() {
        $sql = "SELECT DISTINCT c.MaCoSo, c.TenCoSo 
                FROM promotions p 
                JOIN coso c ON p.ma_co_so = c.MaCoSo 
                ORDER BY c.MaCoSo ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>