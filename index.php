<?php
/**
 * File: index.php
 * Deskripsi: Router utama aplikasi (MVC) - Updated with Edit Logic
 */

session_start();

// 1. Load Koneksi Database & Controllers
require_once 'config/Database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/TransController.php';

// 2. Inisialisasi Database
$database = new Database();
$db = $database->getConnection();

// 3. Ambil parameter URL (default adalah login)
$url = isset($_GET['url']) ? $_GET['url'] : 'login';

// --- LOGIKA KHUSUS AKSI ADMIN (ADD, EDIT, & DELETE) ---

// Aksi Tambah Produk
if ($url == 'add_product' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php?url=login");
        exit();
    }
    
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $query = "INSERT INTO products (name, price, image) VALUES (:name, :price, :image)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image', $image);
    
    if ($stmt->execute()) {
        header("Location: index.php?url=admin/dashboard&status=success");
    } else {
        header("Location: index.php?url=admin/dashboard&status=failed");
    }
    exit();
}

// === LOGIKA EDIT PRODUK (BARU TAMBAHKAN DI SINI) ===
if ($url == 'edit_product' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php?url=login");
        exit();
    }
    
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $query = "UPDATE products SET name = :name, price = :price, image = :image WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image', $image);
    
    if ($stmt->execute()) {
        header("Location: index.php?url=admin/dashboard&status=updated");
    } else {
        header("Location: index.php?url=admin/dashboard&status=failed");
    }
    exit();
}

// Aksi Hapus Produk
if ($url == 'delete_product' && isset($_GET['id'])) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
        header("Location: index.php?url=login");
        exit(); 
    }
    
    $id = $_GET['id'];
    $query = "DELETE FROM products WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?url=admin/dashboard&msg=deleted");
    }
    exit();
}



// --- ROUTING TAMPILAN ---
switch ($url) {
    case 'login':
        if (isset($_SESSION['role'])) {
            $redirect = ($_SESSION['role'] == 'admin') ? 'admin/dashboard' : 'catalog';
            header("Location: index.php?url=$redirect");
            exit();
        }
        require_once 'views/login.php';
        break;

    case 'logout':
        $auth = new AuthController($db);
        $auth->logout(); 
        header("Location: index.php?url=login");
        exit();
        break;

    case 'catalog':
        if (!isset($_SESSION['role'])) { header("Location: index.php?url=login"); exit(); }
        require_once 'views/user/catalog.php';
        break;

    case 'checkout':
        if (!isset($_SESSION['role'])) { header("Location: index.php?url=login"); exit(); }
        require_once 'views/user/checkout.php';
        break;

    case 'admin/dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?url=login");
            exit();
        }
        require_once 'views/admin/dashboard.php';
        break;

    default:
        header("Location: index.php?url=login");
        exit();
        break;
}