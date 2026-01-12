<?php
include 'views/layout/navbar.php';
$trans = new TransController($db);

// Ambil parameter Pencarian dan Pagination
$search = $_GET['search'] ?? '';
$page = $_GET['p'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$products = $trans->getProducts($search, $limit, $offset);
?>
<div class="container mt-4">
    <h2>Manajemen Produk</h2>
    
    <form class="d-flex mb-3" method="GET">
        <input type="hidden" name="url" value="admin/products">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama produk..." value="<?= $search ?>">
        <button class="btn btn-outline-success" type="submit">Cari</button>
    </form>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $p): ?>
            <tr>
                <td><?= $p['name'] ?></td>
                <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                <td>
                    <button class="btn btn-sm btn-warning">Edit</button>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?url=admin/products&p=<?= $page-1 ?>&search=<?= $search ?>">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#"><?= $page ?></a></li>
            <li class="page-item">
                <a class="page-link" href="?url=admin/products&p=<?= $page+1 ?>&search=<?= $search ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>