<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Filter kode, nama, harga_min, dan harga_max sudah ditangani sepenuhnya oleh
    // server (form GET ke route /master-items -> MasterItemsController@index).
    // Sebelumnya, di sini ada AJAX ke route "master-items/search" yang controller-nya
    // sudah non-aktif (dikomentari) sehingga setiap kali halaman dimuat, DataTable
    // langsung di-clear() tanpa pernah terisi ulang -> hasil filter harga min/max
    // (dan filter lain) terlihat seperti tidak berfungsi meskipun data dari server
    // sudah benar. Baris tersebut dihapus dan digantikan inisialisasi DataTable murni
    // di sisi client, karena data yang ditampilkan sudah difilter oleh server.
    $(document).ready(function () {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'asc']],
        });
    });
</script>
