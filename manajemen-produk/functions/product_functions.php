<?php
/**
 * Product Functions
 * File berisi fungsi-fungsi untuk operasi CRUD produk
 */

/**
 * Mengambil semua data produk dari database
 * @param PDO $db - Koneksi database
 * @return array - Array berisi data produk
 */
function getAllProducts($db) {
    try {
        $query = "SELECT * FROM produk ORDER BY id DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting products: " . $e->getMessage());
        return [];
    }
}

/**
 * Mengambil data produk berdasarkan ID
 * @param PDO $db - Koneksi database
 * @param int $id - ID produk
 * @return array|null - Data produk atau null jika tidak ditemukan
 */
function getProductById($db, $id) {
    try {
        $query = "SELECT * FROM produk WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting product: " . $e->getMessage());
        return null;
    }
}

/**
 * Menambah produk baru ke database
 * @param PDO $db - Koneksi database
 * @param string $namaProduk - Nama produk
 * @param float $harga - Harga produk
 * @param int $stok - Jumlah stok
 * @return bool - True jika berhasil, false jika gagal
 */
function createProduct($db, $namaProduk, $harga, $stok) {
    try {
        // Validasi input
        if (empty($namaProduk) || empty($harga) || empty($stok)) {
            return false;
        }

        $query = "INSERT INTO produk (nama_produk, harga, stok) VALUES (:nama_produk, :harga, :stok)";
        $stmt = $db->prepare($query);
        
        // Sanitasi dan binding parameter
        $namaProduk = htmlspecialchars(strip_tags($namaProduk));
        $harga = (float) $harga;
        $stok = (int) $stok;
        
        $stmt->bindParam(':nama_produk', $namaProduk);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':stok', $stok);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error creating product: " . $e->getMessage());
        return false;
    }
}

/**
 * Memperbarui data produk
 * @param PDO $db - Koneksi database
 * @param int $id - ID produk
 * @param string $namaProduk - Nama produk
 * @param float $harga - Harga produk
 * @param int $stok - Jumlah stok
 * @return bool - True jika berhasil, false jika gagal
 */
function updateProduct($db, $id, $namaProduk, $harga, $stok) {
    try {
        // Validasi input
        if (empty($id) || empty($namaProduk) || empty($harga) || empty($stok)) {
            return false;
        }

        $query = "UPDATE produk SET nama_produk = :nama_produk, harga = :harga, stok = :stok WHERE id = :id";
        $stmt = $db->prepare($query);
        
        // Sanitasi dan binding parameter
        $id = (int) $id;
        $namaProduk = htmlspecialchars(strip_tags($namaProduk));
        $harga = (float) $harga;
        $stok = (int) $stok;
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_produk', $namaProduk);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':stok', $stok);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        return false;
    }
}

/**
 * Menghapus produk dari database
 * @param PDO $db - Koneksi database
 * @param int $id - ID produk yang akan dihapus
 * @return bool - True jika berhasil, false jika gagal
 */
function deleteProduct($db, $id) {
    try {
        if (empty($id)) {
            return false;
        }

        $query = "DELETE FROM produk WHERE id = :id";
        $stmt = $db->prepare($query);
        $id = (int) $id;
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

/**
 * Menghitung total produk
 * @param PDO $db - Koneksi database
 * @return int - Jumlah total produk
 */
function countProducts($db) {
    try {
        $query = "SELECT COUNT(*) as total FROM produk";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    } catch (PDOException $e) {
        error_log("Error counting products: " . $e->getMessage());
        return 0;
    }
}
?>