<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Filter kode/nama ditangani oleh server (form GET), sehingga di sini
    // DataTable hanya digunakan untuk styling/sorting atas data yang sudah difilter.
    $(document).ready(function () {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'asc']],
        });
    });
</script>
