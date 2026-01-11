<?php
class CommentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Lấy danh sách bình luận cho trang Admin (kèm tên User và tên SP)
    public function getAllComments() {
        // JOIN 3 bảng: comments, users, product
        $sql = "SELECT c.*, u.name as user_name, p.name as product_name, p.image as product_image
                FROM comments c
                JOIN users u ON c.user_id = u.id
                JOIN product p ON c.product_id = p.id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy bình luận theo sản phẩm (cho trang Detail - Đã có)
    public function getCommentsByProductId($productId) {
        $sql = "SELECT c.*, u.name as user_name 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.product_id = :pid
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pid' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Thêm bình luận (Đã có)
    public function addComment($userId, $productId, $content, $rating) {
        $sql = "INSERT INTO comments (user_id, product_id, content, rating, created_at) 
                VALUES (:uid, :pid, :content, :rating, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'uid' => $userId,
            'pid' => $productId,
            'content' => $content,
            'rating' => $rating
        ]);
    }

    // 4. Xóa bình luận (Dành cho Admin)
    public function deleteComment($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    public function replyComment($userId, $productId, $content, $parentId) {
    $sql = "INSERT INTO comments (user_id, product_id, content, rating, parent_id, created_at) 
            VALUES (?, ?, ?, 5, ?, NOW())"; // Mặc định rating 5 sao cho phản hồi Admin
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$userId, $productId, $content, $parentId]);
}
}