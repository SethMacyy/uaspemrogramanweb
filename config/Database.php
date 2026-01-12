<?php
class Database {
    // Sesuaikan dengan pengaturan database Anda
    private $host = "127.0.0.1";
    private $port = "3306"; // Sesuai dengan gambar yang Anda berikan
    private $db_name = "uas_db"; // Pastikan nama DB ini sama dengan yang di phpMyAdmin
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // String koneksi menggunakan PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            // Mengatur mode error PDO ke exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Gagal koneksi: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>