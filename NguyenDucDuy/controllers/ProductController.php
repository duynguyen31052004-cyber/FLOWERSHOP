<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/CommentModel.php';

class ProductController 
{
    private PDO $db;
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private OrderModel $orderModel;
    private CommentModel $commentModel;
    private string $uploadDir = 'uploads/';

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $database = new Database();
        $conn = $database->getConnection();
        if (!$conn) exit("Lỗi kết nối database.");
        
        $this->db = $conn;
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->orderModel = new OrderModel($this->db);
        $this->commentModel = new CommentModel($this->db);
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['auth']) || trim((string)($_SESSION['auth']['role'] ?? '')) !== 'admin') {
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(403);
            exit('Chỉ quản trị viên mới có quyền thực hiện chức năng này.');
        }
    }

    // ==========================================
    // CÁC HÀM CÔNG KHAI (Khách hàng & Tìm kiếm)
    // ==========================================

    public function index() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 12; 
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getProducts($keyword, $limit, $offset);
        $totalProducts = $this->productModel->countProducts($keyword);
        $totalPages = ceil($totalProducts / $limit);

        if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] === 'admin') {
             include __DIR__ . '/../views/product/list.php';
        } else {
             include __DIR__ . '/../views/home/main.php';
        }
    }

    public function detail($id): void {
        $id = (int)$id;
        $product = $this->productModel->getProductById($id);
        if (!$product) die("Sản phẩm không tồn tại."); 

        $comments = $this->commentModel->getCommentsByProductId($id);
        $relatedProducts = $this->productModel->getRelatedProducts((int)$product['category_id'], $id, 4);

        include __DIR__ . '/../views/product/detail.php';
    }

    public function postComment(): void {
        $userId = (int)$_SESSION['auth']['id'];
        $productId = (int)$_POST['product_id'];
        $content = trim(htmlspecialchars((string)$_POST['content']));
        $rating = (int)$_POST['rating'];

        if (!empty($content) && $rating >= 1 && $rating <= 5) {
            $this->commentModel->addComment($userId, $productId, $content, $rating);
        }
        header('Location: ' . BASE_URL . 'product/detail/' . $productId);
        exit;
    }

    public function replyComment(): void {
        if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'admin') {
            die("Chỉ Admin mới có quyền phản hồi.");
        }
    
        $userId = (int)$_SESSION['auth']['id'];
        $productId = (int)$_POST['product_id'];
        $parentId = (int)$_POST['parent_id'];
        $content = trim(htmlspecialchars((string)$_POST['content']));
    
        if (!empty($content)) {
            $this->commentModel->replyComment($userId, $productId, $content, $parentId);
        }
        header('Location: ' . BASE_URL . 'product/detail/' . $productId);
        exit;
    }

    public function searchAjax() {
        $keyword = $_GET['keyword'] ?? '';
        
        if (empty($keyword)) {
            echo json_encode([]); 
            return;
        }
    
        $products = $this->productModel->searchProducts($keyword);
        
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    // ==========================================
    // GIỎ HÀNG & THANH TOÁN
    // ==========================================

    // 👇 HÀM NÀY ĐÃ ĐƯỢC CẬP NHẬT ĐỂ TẠO THÔNG BÁO 👇
    public function addToCart($id): void {
        $id = (int)$id; 
        $p = $this->productModel->getProductById($id);
        
        if ($p) {
            // Khởi tạo giỏ hàng nếu chưa có
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Thêm hoặc tăng số lượng
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $p['id'], 
                    'name' => $p['name'], 
                    'price' => $p['price'], 
                    'image' => $p['image'], 
                    'quantity' => 1
                ];
            }

            // --- TẠO THÔNG BÁO CHO USER ---
            // Session này sẽ được file main.php đọc để hiện Popup
            $_SESSION['alert_success'] = "Đã thêm '" . htmlspecialchars($p['name']) . "' vào giỏ hàng!";
            
            // (Tùy chọn) Lưu thông báo cho Admin vào DB nếu bạn đã tạo bảng notifications
            // $this->orderModel->addNotification("Khách hàng vừa thêm " . $p['name'], 'cart');
        }

        // Quay lại trang trước đó (để User thấy thông báo) thay vì nhảy sang trang Cart
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'product/list');
        header('Location: ' . $redirectUrl); 
        exit;
    }

    public function cart(): void { 
        $cart = $_SESSION['cart'] ?? []; 
        include __DIR__ . '/../views/product/Cart.php'; 
    }

    public function removeFromCart($id): void { 
        unset($_SESSION['cart'][(int)$id]); 
        header('Location: '.BASE_URL.'product/cart'); exit; 
    }
    
    public function clearCart(): void {
        unset($_SESSION['cart']);
        header('Location: '.BASE_URL.'product/cart'); exit;
    }

    public function checkout(): void { 
        if(empty($_SESSION['cart'])){ header('Location: '.BASE_URL); exit; }
        $cart = $_SESSION['cart']; 
        $total = 0; 
        foreach($cart as $i) { $total += (float)$i['price'] * (int)$i['quantity']; }
        include __DIR__ . '/../views/product/checkout.php'; 
    }

    public function processCheckout(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) { 
            header('Location: '.BASE_URL.'product/cart'); exit; 
        }

        $userInfo = [
            'user_id' => $_SESSION['auth']['id'] ?? null, 
            'name' => trim($_POST['name']), 
            'phone' => trim($_POST['phone']), 
            'address' => trim($_POST['address']),
            'payment_method' => $_POST['payment_method'] ?? 'cod'
        ];

        $total = 0; 
        foreach($_SESSION['cart'] as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }

        $newId = $this->orderModel->createOrder($userInfo, $_SESSION['cart'], (float)$total);

        if($newId){ 
            $_SESSION['last_order_id'] = $newId; 
            unset($_SESSION['cart']); 

            if ($userInfo['payment_method'] === 'sepay') {
                header('Location: '.BASE_URL.'product/payment_qr'); 
            } else {
                header('Location: '.BASE_URL.'product/orderConfirmation'); 
            }
            exit; 
        } else {
            echo "Lỗi khi tạo đơn hàng.";
        }
    }

    public function payment_qr(): void {
        $orderId = $_SESSION['last_order_id'] ?? null;
        if (!$orderId) { header('Location: ' . BASE_URL); exit; }

        $order = $this->orderModel->getOrderById((int)$orderId);
        
        if (!$order) {
            echo "Lỗi: Không tìm thấy thông tin đơn hàng #" . $orderId;
            exit;
        }

        // THÔNG TIN NGÂN HÀNG
        $bankId      = "TCB"; 
        $accountNo   = "19037908998016"; 
        $accountName = "NGUYEN DUC DUY"; 
        $amount      = (int)($order['total_amount'] ?? 0); 
        $description = "DH" . $orderId; 

        $params = http_build_query([
            'amount' => $amount,
            'addInfo' => $description,
            'accountName' => $accountName
        ]);

        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-print.jpg?{$params}";

        include __DIR__ . '/../views/product/payment_qr.php';
    }

    public function sepay_webhook() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $description = $data['content']; 
            $amount = (float)$data['transferAmount']; 

            preg_match('/DH(\d+)/', $description, $matches);
            $orderId = isset($matches[1]) ? (int)$matches[1] : 0;

            if ($orderId > 0) {
                $this->orderModel->updateStatus($orderId, 'paid');
                echo "Success"; 
                exit();
            }
        }
        echo "Invalid Data";
    }

    public function payment_webhook() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $description = $data['description']; 
            $amount = (float)$data['amount'];    

            preg_match('/DH(\d+)/', $description, $matches);
            $orderId = isset($matches[1]) ? (int)$matches[1] : 0;

            if ($orderId > 0) {
                $this->orderModel->updateStatus($orderId, 'paid');
                echo json_encode(['status' => 'success', 'message' => 'Đã xác nhận đơn hàng #' . $orderId]);
                exit();
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    }

    public function fake_payment_confirm($orderId) {
        $this->orderModel->updateStatus((int)$orderId, 'paid');
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }

    public function orderConfirmation() {
        $id = $_SESSION['last_order_id'] ?? null;
        if (!$id) { header('Location: ' . BASE_URL); exit; }

        $order = $this->orderModel->getOrderById((int)$id);
        $items = $this->orderModel->getOrderDetails((int)$id);

        include __DIR__ . '/../views/product/orderconfirmation.php';
    }

    public function history(): void {
        if (empty($_SESSION['auth'])) {
            header('Location: ' . BASE_URL . 'auth/login'); exit;
        }

        $userId = (int)$_SESSION['auth']['id'];
        $orders = $this->orderModel->getOrdersByUserId($userId); 
        include __DIR__ . '/../views/product/history.php';
    }

    public function orderDetail($id) {
        $order = $this->orderModel->getOrderById((int)$id);
        $orderDetails = $this->orderModel->getOrderDetails((int)$id); 

        include __DIR__ . '/../views/product/order_detail.php';
    }

    // ==========================================
    // QUẢN LÝ SẢN PHẨM (Admin)
    // ==========================================

    public function list(): void {
        $this->requireAdmin();
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; $offset = ($page - 1) * $limit;
        $products = $this->productModel->getProducts($keyword, $limit, $offset);
        $totalProducts = $this->productModel->countProducts($keyword);
        $totalPages = ceil($totalProducts / $limit);
        include __DIR__ . '/../views/product/list.php';
    }

    public function add(): void {
        $this->requireAdmin();
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../views/product/add.php';
    }

    public function save(): void {
        validateCsrf();
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location:'.BASE_URL.'product/list'); exit; }
        
        $name = trim($_POST['name']); 
        $desc = trim($_POST['description']); 
        $price = (float)$_POST['price']; 
        $cat = (int)$_POST['category_id'];
        
        $imagePath = null; $errors = [];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image'], $errors);
        }
        $this->productModel->addProduct($name, $desc, $price, $cat, $imagePath);
        header('Location: '.BASE_URL.'product/list'); exit;
    }

    public function edit($id): void {
        $this->requireAdmin();
        $product = $this->productModel->getProductById((int)$id);
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../views/product/edit.php';
    }

    public function update(): void {
        $this->requireAdmin(); 

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            header('Location: ' . BASE_URL . 'product/list'); 
            exit; 
        }
        
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']); 
        $desc = trim($_POST['description']); 
        $price = (float)$_POST['price']; 
        $cat = (int)$_POST['category_id'];
        
        $oldProduct = $this->productModel->getProductById($id);
        if (!$oldProduct) {
            header('Location: ' . BASE_URL . 'product/list'); 
            exit;
        }

        $imagePath = $oldProduct['image']; 

        $errors = [];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImage = $this->uploadImage($_FILES['image'], $errors);
            if ($newImage) {
                $imagePath = $newImage; 
            }
        }

        $this->productModel->updateProduct($id, $name, $desc, $price, $cat, $imagePath);
        
        header('Location: ' . BASE_URL . 'product/list'); 
        exit;
    }

    public function delete($id): void {
        $this->requireAdmin();
        $this->productModel->deleteProduct((int)$id);
        header('Location: '.BASE_URL.'product/list'); exit;
    }

    private function uploadImage(array $file, array &$errors): ?string {
        $allowed = ['jpg', 'jpeg', 'png', 'webp']; 
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){ $errors[]="Ảnh không hợp lệ"; return null; }
        $fn = time().'_'.basename($file['name']); 
        $target = dirname(__DIR__, 2).'/'.$this->uploadDir.$fn;
        if(move_uploaded_file($file['tmp_name'], $target)) return $this->uploadDir.$fn;
        return null;
    }
    // ==========================================
    // AJAX UPDATE CART (Thêm hàm này)
    // ==========================================
    public function updateCartAjax() {
        header('Content-Type: application/json');

        // 1. Lấy dữ liệu gửi lên
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        // 2. Kiểm tra tồn tại
        if ($id > 0 && isset($_SESSION['cart'][$id])) {
            // Cập nhật số lượng (tối thiểu là 1)
            if ($qty < 1) $qty = 1;
            $_SESSION['cart'][$id]['quantity'] = $qty;

            // 3. Tính toán lại các con số
            $itemPrice = (float)$_SESSION['cart'][$id]['price'];
            $lineTotal = $itemPrice * $qty; // Thành tiền của sản phẩm này

            // Tính tổng giỏ hàng
            $totalMoney = 0;
            $totalItems = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalMoney += (float)$item['price'] * (int)$item['quantity'];
                $totalItems += (int)$item['quantity'];
            }

            // 4. Trả về JSON
            echo json_encode([
                'status' => 'success',
                'lineTotal' => $lineTotal,           // Tổng tiền dòng này
                'lineTotalFmt' => number_format($lineTotal, 0, ',', '.') . ' đ',
                'totalMoney' => $totalMoney,         // Tổng tiền cả giỏ
                'totalMoneyFmt' => number_format($totalMoney, 0, ',', '.'),
                'totalItems' => $totalItems
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tìm thấy']);
        }
        exit;
}
}