<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController 
{
    private PDO $db;
    private OrderModel $orderModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }

    // =================================================================
    // 🛡️ ADMIN FUNCTIONS
    // =================================================================
    public function index(): void {
        $this->requireAdmin();
        $orders = $this->orderModel->getAllOrders();
        
        // Thống kê nhanh cho Dashboard
        $totalOrders = count($orders);
        $totalRevenue = 0;
        $pendingOrders = 0;
        $completedOrders = 0;

        foreach ($orders as $o) {
            if ($o['status'] === 'completed') {
                $totalRevenue += (float)$o['total_amount'];
                $completedOrders++;
            }
            if ($o['status'] === 'pending') {
                $pendingOrders++;
            }
        }
        include __DIR__ . '/../views/admin/orders/index.php';
    }

    public function updateStatus(): void {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? $_POST['order_id'] ?? 0);
            $status = $_POST['status']; 
            if ($id > 0) $this->orderModel->updateStatus($id, $status);
            header('Location: ' . BASE_URL . 'order/detail/' . $id);
            exit();
        }
    }

    public function stats() {
        $this->requireAdmin();
        $revenueData = $this->orderModel->getRevenueLast30Days();
        
        $labels = []; $data = [];
        if (!empty($revenueData)) {
            foreach ($revenueData as $row) {
                $labels[] = date('d/m', strtotime($row['date'])); 
                $data[] = (int)$row['total'];
            }
        }

        $topProducts = $this->orderModel->getTopSellingProducts();
        include __DIR__ . '/../views/admin/orders/stats.php';
    }

    // =================================================================
    // 🌍 USER FUNCTIONS (THANH TOÁN & ĐƠN HÀNG)
    // =================================================================

    public function detail($id) {
        $this->requireLogin();
        $id = (int)$id; 
        $order = $this->orderModel->getOrderById($id);
        $this->checkOrderOwner($order); 
        $orderDetails = $this->orderModel->getOrderDetails($id);
        include __DIR__ . '/../views/admin/orders/detail.php';
    }

    public function payment($id) {
        $this->requireLogin();
        $id = (int)$id;
        $order = $this->orderModel->getOrderById($id);
        $this->checkOrderOwner($order);

        if ($order['status'] !== 'pending') {
            header('Location: ' . BASE_URL . 'order/orderConfirmation/' . $id);
            exit();
        }

        // Thông tin thanh toán TECHCOMBANK
        $amount = (float)$order['total_amount'];
        $description = "THANH TOAN DON $id";
        $accountNo = '19037908998016';   
        $bankId = 'TCB';                 
        $accountName = 'NGUYEN DUC DUY'; 
        $orderId = $id;

        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact.png?amount={$amount}&addInfo=" . urlencode($description) . "&accountName=" . urlencode($accountName);

        include __DIR__ . '/../views/product/payment_qr.php';
    }

    public function orderConfirmation($id) {
        $this->requireLogin();
        $id = (int)$id;
        $order = $this->orderModel->getOrderById($id);
        $this->checkOrderOwner($order);
        
        include __DIR__ . '/../views/product/orderConfirmation.php';
    }

    public function checkout() {
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . 'product/cart'); exit();
        }

        $cartItems = $_SESSION['cart'];
        $totalAmount = 0;
        foreach ($cartItems as $item) $totalAmount += $item['price'] * $item['quantity'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $messageCard = !empty($_POST['message_card']) ? trim($_POST['message_card']) : null;
            $deliveryDate = !empty($_POST['delivery_date']) ? $_POST['delivery_date'] : null;
            $paymentMethod = $_POST['payment_method'] ?? 'cod';

            if (empty($name) || empty($phone) || empty($address)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
                header('Location: ' . BASE_URL . 'product/checkout'); exit();
            }

            $userInfo = [
                'user_id' => $_SESSION['auth']['id'] ?? null,
                'name' => $name, 'phone' => $phone, 'address' => $address,
                'message_card' => $messageCard, 'delivery_date' => $deliveryDate
            ];

            $orderId = $this->orderModel->createOrder($userInfo, $cartItems, $totalAmount);

            if ($orderId > 0) {
                unset($_SESSION['cart']); 
                if ($paymentMethod === 'cod') {
                    header('Location: ' . BASE_URL . 'order/orderConfirmation/' . $orderId);
                } else {
                    include __DIR__ . '/../views/product/payment_loading.php';
                }
                exit();
            } else {
                $_SESSION['error'] = 'Lỗi tạo đơn hàng.';
                header('Location: ' . BASE_URL . 'product/checkout'); exit();
            }
        }
        include __DIR__ . '/../views/product/checkout.php'; 
    }

    public function fake_payment_confirm($id) {
        header('Content-Type: application/json');
        $id = (int)$id;
        if ($id > 0) {
            $this->orderModel->updateStatus($id, 'completed'); 
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi ID']);
        }
        exit();
    }

    public function check_order_status($id) {
        header('Content-Type: application/json');
        $id = (int)$id;
        $order = $this->orderModel->getOrderById($id);
        echo json_encode(['status' => $order['status'] ?? 'pending']);
        exit();
    }

    public function history(): void {
        $this->requireLogin();
        $orders = $this->orderModel->getOrdersByUserId($_SESSION['auth']['id']);
        include __DIR__ . '/../views/product/history.php';
    }

    // =================================================================
    // 🔒 PRIVATE HELPER METHODS
    // =================================================================
    private function requireAdmin() {
        if (!isset($_SESSION['auth']) || ($_SESSION['auth']['role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'auth/login'); exit();
        }
    }

    private function requireLogin() {
        if (empty($_SESSION['auth'])) {
            header('Location: ' . BASE_URL . 'auth/login'); exit();
        }
    }

    private function checkOrderOwner($order) {
        $currentUserId = $_SESSION['auth']['id'];
        $userRole = $_SESSION['auth']['role'] ?? 'user';
        if (!$order || ($userRole !== 'admin' && $order['user_id'] != $currentUserId)) {
            die("Bạn không có quyền truy cập đơn hàng này.");
        }
    }
} // Kết thúc class