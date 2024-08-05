<x-dashboard.main title="Transaksi">
    <div class="grid sm:grid-cols-3 gap-5 md:gap-6">
        @foreach (['jenis_transaksi', 'transaksi_terbaru', 'nominal_transaksi'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'jenis_transaksi' ? 'bg-blue-300' : '' }}
                  {{ $type == 'transaksi_terbaru' ? 'bg-amber-300' : '' }}
                  {{ $type == 'nominal_transaksi' ? 'bg-red-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full uppercase"></span>
                <div>
                    <p class="text-sm font-medium capitalize line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold line-clamp-1 uppercase">
                        {{ $type == 'jenis_transaksi' ? $transaksi_terbaru->transaksi_jenis ?? '-' : '' }}
                        {{ $type == 'transaksi_terbaru' ? $transaksi_terbaru->transaksi_keterangan ?? '-' : '' }}
                        {{ $type == 'nominal_transaksi' ? 'Rp ' . number_format($transaksi_terbaru->transaksi_nominal ?? 0, 0, ',', '.') : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @foreach (['tambah_transaksi'] as $item)
        <div onclick="{{ $item . '_modal' }}.showModal()"
            class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
            <div>
                <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize ">
                    {{ str_replace('_', ' ', $item) }}
                </h1>
                <p class="text-sm opacity-60 ">
                    {{ $item == 'tambah_transaksi' ? 'Menambahkan Transaksi' : '' }}
                </p>
            </div>
            <x-lucide-plus class="{{ $item == 'tambah_transaksi' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60 " />
        </div>
    @endforeach
    <div class="flex gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                    Monitoring Transaksi
                </h1>
                <p class="text-sm opacity-60">
                    Pencatatan Transaksi
                </p>
            </div>
            <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="uppercase font-bold text-center" rowspan="2">No</th>
                                <th class="uppercase font-bold text-center" rowspan="2">Tanggal</th>
                                <th class="uppercase font-bold text-center" rowspan="2">Kategori</th>
                                <th class="uppercase font-bold text-center" rowspan="2">Keterangan</th>
                                <th class="uppercase font-bold text-center" rowspan="2">bank</th>
                                <th class="uppercase font-bold text-center" colspan="2">Jenis</th>
                                <th class="uppercase font-bold text-center" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="uppercase font-bold text-center">Pemasukan</th>
                                <th class="uppercase font-bold text-center">Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($transaksi->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center font-semibold text-gray-500">Tidak ada
                                        transaksi</td>
                                </tr>
                            @else
                                @foreach ($transaksi as $i => $item)
                                    <tr class="text-center">
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $item->transaksi_tanggal }}</td>
                                        <td class="font-semibold uppercase">{{ $item->kategori->kategori }}</td>
                                        <td class="font-semibold uppercase">{{ $item->transaksi_keterangan }}</td>
                                        <td class="font-semibold uppercase">
                                            {{ $item->bank->nomor_rekening }} a/n {{ $item->bank->pemilik }}
                                            ({{ $item->bank->nama_bank }})
                                        </td>
                                        <td class="font-semibold">
                                            @if ($item->transaksi_jenis == 'pemasukan')
                                                Rp{{ number_format($item->transaksi_nominal ?? 0, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="font-semibold">
                                            @if ($item->transaksi_jenis == 'pengeluaran')
                                                Rp{{ number_format($item->transaksi_nominal ?? 0, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="flex items-center gap-4">
                                            <x-lucide-square-pen
                                                onclick="update_transaksi_modal_{{ $item->id_transaksi }}.showModal();initUpdate('transaksi', {{ $item }})"
                                                class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                            <dialog id="update_transaksi_modal_{{ $item->id_transaksi }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <form
                                                    action="{{ route('update.transaksi', ['id_transaksi' => $item->id_transaksi]) }}"
                                                    method="POST" class="modal-box bg-secondary">
                                                    @method('PUT')
                                                    @csrf
                                                    <h3 class="modal-title capitalize">
                                                        Update Transaksi tanggal {{ $item->transaksi_tanggal }}
                                                    </h3>
                                                    <div class="modal-body">
                                                        <div class="input-label">
                                                            <h1 class="label">Masukkan Tanggal Transaksi:</h1>
                                                            <input required name="transaksi_tanggal" type="date"
                                                                value="{{ $item->transaksi_tanggal }}">
                                                            @error($type)
                                                                <span class="validated">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="input-label">
                                                            <h1 class="label">Kategori Transaksi:</h1>
                                                            <select required name="id_kategori"
                                                                class="select select-bordered w-full">
                                                                <option value="">--- Pilih Kategori ---</option>
                                                                @foreach ($kategori as $kat)
                                                                    <option value="{{ $kat->id_kategori }}"
                                                                        {{ $item->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                                                        {{ $kat->kategori }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-label">
                                                            <h1 class="label">Masukkan Keterangan Transaksi:</h1>
                                                            <textarea name="transaksi_keterangan" id="transaksi_keterangan" class="textarea textarea-bordered border-white">{{ $item->transaksi_keterangan }}</textarea>
                                                        </div>
                                                        <div class="input-label">
                                                            <h1 class="label">Rekening Bank:</h1>
                                                            <select required name="id_bank"
                                                                class="select select-bordered w-full">
                                                                <option value="">--- Pilih Rekening ---</option>
                                                                @foreach ($rekening as $rek)
                                                                    <option value="{{ $rek->id_bank }}"
                                                                        {{ $item->id_bank == $rek->id_bank ? 'selected' : '' }}>
                                                                        {{ $rek->nomor_rekening }} a/n
                                                                        {{ $rek->pemilik }} ({{ $rek->nama_bank }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-label">
                                                            <h1 class="label">Jenis Transaksi:</h1>
                                                            <select required name="transaksi_jenis"
                                                                class="select select-bordered w-full">
                                                                <option value="">--- Pilih Jenis Transaksi ---
                                                                </option>
                                                                <option value="pemasukan"
                                                                    {{ $item->transaksi_jenis == 'pemasukan' ? 'selected' : '' }}>
                                                                    Pemasukan</option>
                                                                <option value="pengeluaran"
                                                                    {{ $item->transaksi_jenis == 'pengeluaran' ? 'selected' : '' }}>
                                                                    Pengeluaran</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-label">
                                                            <h1 class="label">Nominal Transaksi:</h1>
                                                            <input required name="transaksi_nominal" type="text"
                                                                value="{{ $item->transaksi_nominal }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="button" class="btn"
                                                            onclick="document.getElementById('update_transaksi_modal_{{ $item->id_transaksi }}').close()">Tutup</button>
                                                        <button type="submit" class="btn btn-success capitalize">Update
                                                            Bank</button>
                                                    </div>
                                                </form>
                                            </dialog>
                                            @if (session()->get('role') !== 'user')
                                                <x-lucide-trash-2
                                                    onclick="document.getElementById('delete_modal_{{ $item->id_transaksi }}').showModal();"
                                                    class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                <dialog id="delete_modal_{{ $item->id_transaksi }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <form
                                                        action="{{ route('delete.transaksi', ['id_transaksi' => $item->id_transaksi]) }}"
                                                        method="POST" class="modal-box bg-secondary">
                                                        @csrf
                                                        @method('DELETE')
                                                        <h3 class="modal-title capitalize">Hapus transaksi</h3>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus transaksi tanggal
                                                                "{{ $item->transaksi_tanggal }}"?</p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button" class="btn"
                                                                onclick="document.getElementById('delete_modal_{{ $item->id_transaksi }}').close()">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-danger capitalize">Hapus</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main>

@foreach (['tambah_transaksi'] as $item)
    @php
        $type = explode('_', $item)[1];
        $route = 'store.' . $type;
    @endphp
    <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
        <form action="{{ route('store.transaksi') }}" method="POST" class="modal-box bg-secondary">
            @csrf
            <h3 class="modal-title capitalize">
                Tambah Transaksi Baru
            </h3>
            <div class="modal-body">
                <div class="input-label">
                    <h1 class="label">Masukkan Tanggal Transaksi:</h1>
                    <input required name="transaksi_tanggal" type="date">
                    @error('transaksi_tanggal')
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Kategori Transaksi:</h1>
                    <select required name="id_kategori" class="select select-bordered w-full">
                        <option value="">--- Pilih Kategori ---</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Keterangan Transaksi:</h1>
                    <textarea name="transaksi_keterangan" id="transaksi_keterangan" class="textarea textarea-bordered border-white"></textarea>
                </div>
                <div class="input-label">
                    <h1 class="label">Rekening Bank:</h1>
                    <select required name="id_bank" class="select select-bordered w-full">
                        <option value="">--- Pilih Rekening ---</option>
                        @foreach ($rekening as $rek)
                            <option value="{{ $rek->id_bank }}">
                                {{ $rek->nomor_rekening }} a/n {{ $rek->pemilik }} ({{ $rek->nama_bank }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-label">
                    <h1 class="label">Jenis Transaksi:</h1>
                    <select required name="transaksi_jenis" class="select select-bordered w-full">
                        <option value="">--- Pilih Jenis Transaksi ---</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="input-label">
                    <h1 class="label">Nominal Transaksi:</h1>
                    <input required name="transaksi_nominal" type="text">
                    @error('transaksi_nominal')
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('add_transaksi_modal').close()">Tutup</button>
                <button type="submit" class="btn btn-success capitalize">Tambah Transaksi</button>
            </div>
        </form>
    </dialog>
@endforeach
