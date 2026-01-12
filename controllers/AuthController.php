<?php
/**
 * File: controllers/AuthController.php
 * Deskripsi: Menangani login tanpa sistem Hash (Plain Text) sesuai permintaan.
 */

class AuthController {
    private $db;

    // Menghubungkan controller dengan database
    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    /**
     * Fungsi Login dengan pengecekan password langsung (Plain Text)
     */
    public function login($username, $password) {
        try {
            // 1. Mencari user berdasarkan username
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Cek apakah user ada DAN password cocok (Teks Biasa)
            // Menggunakan === untuk memastikan ketepatan penulisan
            if ($user && $password === $user['password']) {
                
                // 3. Simpan data ke Session agar bisa diakses di halaman lain
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role']; // 'admin' atau 'user'
                
                return true; // Berhasil login
            }
            
            return false; // Gagal (username tidak ada atau password tidak pas)

        } catch (PDOException $e) {
            // Jika ada error pada database, kembalikan false
            return false;
        }
    }

    /**
     * Fungsi Logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: login");
        exit();
    }
}