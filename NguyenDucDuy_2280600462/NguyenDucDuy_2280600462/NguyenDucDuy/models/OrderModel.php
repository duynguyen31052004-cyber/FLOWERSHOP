<?php
declare(strict_types=1);

class OrderModel
{
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // 1. Thống kê doanh thu
    public function getRevenueLast30Days() {
        $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as total 
                FROM orders 
                WHERE status = 'completed' 
                AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                GROUP BY DATE(created_at) 
                ORDER BY date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Top sản phẩm
    public function getTopSellingProducts() {
        $sql = "SELECT p.name, SUM(od.quantity) as total_sold 
                FROM order_details od
                JOIN product p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 'completed' 
                GROUP BY p.id, p.name 
                ORDER BY total_sold DESC 
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Tạo đơn hàng (QUAN TRỌNG: CÓ message_card và delivery_date)
    // 3. Tạo đơn hàng
    // 3. Tạo đơn hàng
    public function createOrder(array $userInfo, array $cartItems, float $totalAmount): int {
        try {
            $this->conn->beginTransaction();
            
            // 👇 SỬA CÂU LỆNH SQL: Thêm 'total_price' vào danh sách cột và ':total' vào danh sách giá trị
            $sql = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, message_card, delivery_date, total_amount, total_price, created_at) 
                    VALUES (:uid, :name, :phone, :address, :msg, :date, :total, :total, NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':uid' => $userInfo['user_id'] ?? null,
                ':name' => $userInfo['name'],
                ':phone' => $userInfo['phone'],
                ':address' => $userInfo['address'],
                ':msg' => $userInfo['message_card'] ?? null, 
                ':date' => $userInfo['delivery_date'] ?? null,
                ':total' => $totalAmount // Giá trị này sẽ được điền vào cả 2 cột total_amount và total_price
            ]);
            
            $orderId = (int)$this->conn->lastInsertId();

            $sqlDetail = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                          VALUES (:oid, :pid, :qty, :price)";
            $stmtDetail = $this->conn->prepare($sqlDetail);
            
            foreach ($cartItems as $item) {
                $stmtDetail->execute([
                    ':oid' => $orderId, 
                    ':pid' => $item['id'], 
                    ':qty' => $item['quantity'], 
                    ':price' => $item['price']
                ]);
            }
            
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return 0;
        }
    }

    // 4. Lấy đơn hàng theo ID
    public function getOrderById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // 5. Lấy chi tiết sản phẩm
    public function getOrderDetails(int $orderId): array {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM order_details od
                JOIN product p ON od.product_id = p.id 
                WHERE od.order_id = :oid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Lấy tất cả đơn hàng
    public function getAllOrders(): array {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 8. Update trạng thái
    public function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // 9. Lịch sử đơn hàng
    public function getOrdersByUserId(int $userId): array {
        $sql = "SELECT o.*, 
                       COALESCE(tt.calculated_total, 0) as real_total_amount
                FROM orders o
                LEFT JOIN (
                    SELECT order_id, SUM(price * quantity) as calculated_total 
                    FROM order_details 
                    GROUP BY order_id
                ) tt ON o.id = tt.order_id
                WHERE o.user_id = :uid
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
}