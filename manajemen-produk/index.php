<?php
// index.php - Halaman Utama Aplikasi Manajemen Produk
require_once 'config/database.php';
require_once 'functions/product_functions.php';

// Cek file database
if (!file_exists('config/database.php')) {
    die("Error: File config/database.php tidak ditemukan!");
}
require_once 'config/database.php';

// Cek file functions
if (!file_exists('functions/product_functions.php')) {
    die("Error: File functions/product_functions.php tidak ditemukan!");
}

// Inisialisasi database
$database = new Database();
$db = $database->getConnection();

// Cek koneksi
if ($db === null) {
    die("<div style='padding:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:5px;margin:20px;'>
    <h3>❌ Koneksi Database Gagal!</h3>
    <p>Pastikan:</p>
    <ul>
        <li>XAMPP/MySQL sudah berjalan</li>
        <li>Database 'manajemen_produk' sudah dibuat</li>
        <li>Username dan password di config/database.php sudah benar</li>
    </ul>
    </div>");
}

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $result = createProduct($db, $_POST['nama_produk'], $_POST['harga'], $_POST['stok']);
                $message = $result ? "Produk berhasil ditambahkan!" : "Gagal menambahkan produk!";
                $messageType = $result ? "success" : "danger";
                break;
            
            case 'update':
                $result = updateProduct($db, $_POST['id'], $_POST['nama_produk'], $_POST['harga'], $_POST['stok']);
                $message = $result ? "Produk berhasil diperbarui!" : "Gagal memperbarui produk!";
                $messageType = $result ? "success" : "danger";
                break;
            
            case 'delete':
                $result = deleteProduct($db, $_POST['id']);
                $message = $result ? "Produk berhasil dihapus!" : "Gagal menghapus produk!";
                $messageType = $result ? "success" : "danger";
                break;
        }
    }
}

// Ambil semua data produk
$products = getAllProducts($db);

// Ambil data produk untuk edit jika ada parameter
$editProduct = null;
if (isset($_GET['edit'])) {
    $editProduct = getProductById($db, $_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - PT. Digital Nusantara</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <?php include 'views/header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Alert Message -->
        <?php if (isset($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form Tambah/Edit Produk -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-<?= $editProduct ? 'edit' : 'plus-circle' ?>"></i>
                    <?= $editProduct ? 'Edit Produk' : 'Tambah Produk Baru' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php" id="productForm">
                    <input type="hidden" name="action" value="<?= $editProduct ? 'update' : 'create' ?>">
                    <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk *</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                                   value="<?= $editProduct ? htmlspecialchars($editProduct['nama_produk']) : '' ?>" 
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="harga" class="form-label">Harga (Rp) *</label>
                            <input type="number" class="form-control" id="harga" name="harga" 
                                   value="<?= $editProduct ? $editProduct['harga'] : '' ?>" 
                                   min="0" step="1000" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stok" class="form-label">Stok *</label>
                            <input type="number" class="form-control" id="stok" name="stok" 
                                   value="<?= $editProduct ? $editProduct['stok'] : '' ?>" 
                                   min="0" required>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= $editProduct ? 'Update' : 'Simpan' ?>
                        </button>
                        <?php if ($editProduct): ?>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Produk -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Produk</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php $no = 1; foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($product['nama_produk']) ?></td>
                                    <td>Rp <?= number_format($product['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $product['stok'] > 10 ? 'success' : ($product['stok'] > 0 ? 'warning' : 'danger') ?>">
                                            <?= $product['stok'] ?> unit
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?edit=<?= $product['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['nama_produk']) ?>')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data produk</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'views/footer.php'; ?>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk <strong id="productName"></strong>?</p>
                    <p class="text-muted">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="index.php" id="deleteForm">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>