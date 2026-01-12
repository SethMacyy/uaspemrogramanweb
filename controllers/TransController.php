<?php
/**
 * File: controllers/TransController.php
 * Deskripsi: Menangani pengambilan data produk dari database.
 */

class TransController {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    /**
     * Fungsi PENTING: Untuk mengambil data produk dengan fitur Search & Pagination
     * Fungsi inilah yang dicari oleh catalog.php baris 18
     */
    public function getProducts($search = "", $limit = 6, $offset = 0) {
        try {
            // Query SQL untuk mencari nama produk dan membatasi jumlah data (Pagination)
            $query = "SELECT * FROM products WHERE name LIKE :search ORDER BY id DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            
            // Binding parameter untuk keamanan (mencegah SQL Injection)
            $search_param = "%$search%";
            $stmt->bindParam(':search', $search_param);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Mengembalikan hasil dalam bentuk array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return []; // Kembalikan array kosong jika terjadi error
        }
    }
}