<dialog id="delete_modal" class="modal modal-bottom sm:modal-middle">
    <form class="modal-box" id="delete_form" method="POST">
        @csrf
        @method('DELETE')
        <h3 class="modal-title capitalize">
            Hapus <span id="dl_data_type"></span> <span id="dl_data_nama"></span>
        </h3>
        <div class="modal-body" id="dl_body"></div>
        <div class="modal-action">
            <button class="btn btn-error capitalize text-white" type="submit">
                Yakin
            </button>
            <button type="button" onclick="document.getElementById('delete_modal').close()" class="btn">Batal</button>
        </div>
    </form>
</dialog>

<script>
    function initDelete(type, data) {
        let val;
        switch (type) {
            case 'kategori':
                val = data.kategori || 'Unknown';
                break;
            case 'bank':
                val = data.bank || 'Unknown';
                break;
            case 'transaksi':
                val = data.transaksi || 'Unknown';
                break;
            case 'hutang':
                val = data.hutang || 'Unknown';
                break;
            case 'piutang':
                val = data.piutang || 'Unknown';
                break;
            default:
                val = data.name || 'Unknown';
                break;
        }

        document.getElementById('dl_data_type').innerText = type;
        document.getElementById('dl_data_nama').innerText = `"${val}"`;
        document.getElementById('dl_body').innerHTML = `
        <h1>Yakin ingin menghapus ${type} <strong>"${val}"</strong>? Tindakan ini tidak dapat diurungkan. 
        <strong class="text-red-600"><span class='capitalize'>${type}</span> akan hilang secara permanen.</strong>
        </h1>`;

        const form = document.getElementById('delete_form');
        form.action = `/delete/${type}/${data.id}`;
        document.getElementById('delete_modal').showModal();
    }
</script>
