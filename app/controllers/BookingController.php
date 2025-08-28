<?php
/**
 * Booking Controller - Xử lý đặt bàn
 */

class BookingController extends BaseController {
    
    public function index() {
        $data = [
            'title' => 'Đặt bàn - ' . APP_NAME,
            'tables' => $this->getAvailableTables(),
            'time_slots' => $this->getTimeSlots()
        ];
        
        $this->render('booking/index', $data);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processBooking();
        } else {
            redirect('booking');
        }
    }
    
    public function checkAvailability() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['booking_date'] ?? '';
            $time = $_POST['booking_time'] ?? '';
            $guests = (int)($_POST['guests'] ?? 1);
            
            $availableTables = $this->checkTableAvailability($date, $time, $guests);
            
            $this->json([
                'success' => true,
                'tables' => $availableTables
            ]);
        }
    }
    
    public function myBookings() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $bookings = $this->getUserBookings($user['id']);
        
        $data = [
            'title' => 'Đặt bàn của tôi - ' . APP_NAME,
            'bookings' => $bookings
        ];
        
        $this->render('booking/my-bookings', $data);
    }
    
    public function cancel() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = $_POST['booking_id'] ?? 0;
            $user = $this->getCurrentUser();
            
            if ($this->cancelBooking($bookingId, $user['id'])) {
                $_SESSION['message'] = 'Hủy đặt bàn thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Không thể hủy đặt bàn!';
                $_SESSION['message_type'] = 'error';
            }
        }
        
        redirect('booking/my-bookings');
    }
    
    private function processBooking() {
        try {
            $data = [
                'customer_name' => $_POST['customer_name'] ?? '',
                'customer_phone' => $_POST['customer_phone'] ?? '',
                'customer_email' => $_POST['customer_email'] ?? '',
                'booking_date' => $_POST['booking_date'] ?? '',
                'booking_time' => $_POST['booking_time'] ?? '',
                'guests' => (int)($_POST['guests'] ?? 1),
                'table_id' => (int)($_POST['table_id'] ?? 0),
                'special_requests' => $_POST['special_requests'] ?? '',
                'user_id' => $this->isLoggedIn() ? $this->getCurrentUser()['id'] : null
            ];
            
            // Validation
            if (empty($data['customer_name']) || empty($data['customer_phone']) || 
                empty($data['booking_date']) || empty($data['booking_time'])) {
                throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc!');
            }
            
            // Kiểm tra tính khả dụng
            if (!$this->isTableAvailable($data['table_id'], $data['booking_date'], $data['booking_time'])) {
                throw new Exception('Bàn đã được đặt trong thời gian này!');
            }
            
            // Tạo booking
            $bookingId = $this->createBooking($data);
            
            if ($bookingId) {
                $_SESSION['message'] = 'Đặt bàn thành công! Mã đặt bàn: #' . $bookingId;
                $_SESSION['message_type'] = 'success';
                redirect('booking/success?id=' . $bookingId);
            } else {
                throw new Exception('Có lỗi xảy ra khi đặt bàn!');
            }
            
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'error';
            redirect('booking');
        }
    }
    
    private function getAvailableTables() {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM tables 
                WHERE status = 'active' 
                ORDER BY capacity ASC, table_number ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getTimeSlots() {
        return [
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30'
        ];
    }
    
    private function checkTableAvailability($date, $time, $guests) {
        try {
            $stmt = $this->db->prepare("
                SELECT t.* FROM tables t
                WHERE t.capacity >= ? AND t.status = 'active'
                AND t.id NOT IN (
                    SELECT table_id FROM bookings 
                    WHERE booking_date = ? AND booking_time = ? 
                    AND status IN ('confirmed', 'pending')
                )
                ORDER BY t.capacity ASC
            ");
            $stmt->execute([$guests, $date, $time]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function isTableAvailable($tableId, $date, $time) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM bookings 
                WHERE table_id = ? AND booking_date = ? AND booking_time = ? 
                AND status IN ('confirmed', 'pending')
            ");
            $stmt->execute([$tableId, $date, $time]);
            return $stmt->fetchColumn() == 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function createBooking($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO bookings (
                    customer_name, customer_phone, customer_email, 
                    booking_date, booking_time, guests, table_id, 
                    special_requests, user_id, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            
            $stmt->execute([
                $data['customer_name'],
                $data['customer_phone'],
                $data['customer_email'],
                $data['booking_date'],
                $data['booking_time'],
                $data['guests'],
                $data['table_id'],
                $data['special_requests'],
                $data['user_id']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function getUserBookings($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT b.*, t.table_number, t.location 
                FROM bookings b
                LEFT JOIN tables t ON b.table_id = t.id
                WHERE b.user_id = ?
                ORDER BY b.booking_date DESC, b.booking_time DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function cancelBooking($bookingId, $userId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE bookings 
                SET status = 'cancelled', updated_at = NOW()
                WHERE id = ? AND user_id = ? AND status IN ('pending', 'confirmed')
                AND booking_date >= CURDATE()
            ");
            $stmt->execute([$bookingId, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
