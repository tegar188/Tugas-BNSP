/**
 * Custom JavaScript untuk Aplikasi Manajemen Produk
 * Berisi fungsi-fungsi untuk interaksi user
 */

// Fungsi untuk konfirmasi hapus produk
function confirmDelete(productId, productName) {
    // Set data ke modal
    document.getElementById('deleteId').value = productId;
    document.getElementById('productName').textContent = productName;
    
    // Tampilkan modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Auto-hide alert setelah 5 detik
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Validasi form sebelum submit
document.getElementById('productForm')?.addEventListener('submit', function(e) {
    const namaProduk = document.getElementById('nama_produk').value.trim();
    const harga = parseFloat(document.getElementById('harga').value);
    const stok = parseInt(document.getElementById('stok').value);
    
    // Validasi nama produk
    if (namaProduk === '') {
        e.preventDefault();
        alert('Nama produk tidak boleh kosong!');
        document.getElementById('nama_produk').focus();
        return false;
    }
    
    // Validasi harga
    if (harga < 0 || isNaN(harga)) {
        e.preventDefault();
        alert('Harga harus berupa angka positif!');
        document.getElementById('harga').focus();
        return false;
    }
    
    // Validasi stok
    if (stok < 0 || isNaN(stok)) {
        e.preventDefault();
        alert('Stok harus berupa angka positif!');
        document.getElementById('stok').focus();
        return false;
    }
    
    return true;
});

// Format input harga dengan pemisah ribuan
document.getElementById('harga')?.addEventListener('blur', function() {
    let value = this.value.replace(/[^\d]/g, '');
    if (value) {
        this.value = parseFloat(value);
    }
});

// Smooth scroll untuk navigasi
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Konfirmasi sebelum meninggalkan halaman jika form terisi
let formChanged = false;

document.getElementById('productForm')?.addEventListener('input', function() {
    formChanged = true;
});

document.getElementById('productForm')?.addEventListener('submit', function() {
    formChanged = false;
});

window.addEventListener('beforeunload', function (e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
});

// Fungsi untuk format mata uang Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

// Loading button state
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        }
    });
});

// Print functionality (bonus feature)
function printTable() {
    window.print();
}

// Search/Filter table (bonus feature)
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('table tbody');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length - 1; j++) { // -1 to skip action column
            if (cells[j]) {
                const textValue = cells[j].textContent || cells[j].innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

console.log('✅ Aplikasi Manajemen Produk berhasil dimuat');