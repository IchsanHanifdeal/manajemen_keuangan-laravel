<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Apa anda Yakin?',
            text: "anda akan keluar dari sesi login?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
<footer class='p-5 border-t bg-dark'>
    <div class="text-center">
        <span class="block text-sm text-center text-gray-500 :text-gray-400">
            Copyright © {{ date('Y') }} DompetQu™. All Rights Reserved.
        </span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
