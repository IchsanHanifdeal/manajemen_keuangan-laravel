<x-dashboard.main title="Bank">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_bank', 'bank_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_bank' ? 'bg-blue-300' : '' }}
                  {{ $type == 'bank_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold line-clamp-1">
                        {{ $type == 'total_bank' ? $total_bank : '' }}
                        {{ $type == 'bank_terbaru' ? $bank_terbaru->nama_bank ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if (session()->get('role') !== 'user')
        <div class="flex flex-col lg:flex-row gap-5">
            @foreach (['tambah_bank'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize ">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 ">
                            {{ $item == 'tambah_bank' ? 'Menambahkan bank untuk pemasukan maupun pengeluaran' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus class="{{ $item == 'tambah_bank' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60 " />
                </div>
            @endforeach
        </div>
    @endif
    <div class="flex gap-5">
        @foreach (['manajemen_bank'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Buat identitas setiap transaksi dengan menambahkan bank.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach (['no', 'nama bank', 'nomor rekening', 'pemilik', 'saldo bank'] as $item)
                                        <th class="uppercase font-bold text-center">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($bank->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center font-semibold text-gray-500">Tidak ada
                                            rekening bank tersedia</td>
                                    </tr>
                                @else
                                    @foreach ($bank as $i => $item)
                                        <tr class="text-center">
                                            <th>{{ $i + 1 }}</th>
                                            <td class="font-semibold uppercase">{{ $item->nama_bank }}</td>
                                            <td class="font-semibold uppercase">{{ $item->nomor_rekening }}</td>
                                            <td class="font-semibold uppercase">{{ $item->pemilik }}</td>
                                            <td class="font-semibold">
                                                Rp{{ number_format($item->saldo_bank, 0, ',', '.') }}
                                            </td>
                                            @if (session()->get('role') !== 'user')
                                                <td class="flex items-center gap-4">
                                                    <x-lucide-square-pen
                                                        onclick="update_bank_modal_{{ $item->id_bank }}.showModal();initUpdate('bank', {{ $item }})"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                    <dialog id="update_bank_modal_{{ $item->id_bank }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('update.bank', ['id_bank' => $item->id_bank]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @method('PUT')
                                                            @csrf
                                                            <h3 class="modal-title capitalize">
                                                                Update Rekening Bank {{ $item->pemilik }}
                                                            </h3>
                                                            <div class="modal-body">
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nama Bank:
                                                                    </h1>
                                                                    <input required name="bank" type="text"
                                                                        placeholder="Contoh: {{ $item->nama_bank }}"
                                                                        value="{{ $item->nama_bank }}">
                                                                    @error($type)
                                                                        <span class="validated">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nomor Rekening
                                                                        Bank:</h1>
                                                                    <input required name="nomor_rekening" type="number"
                                                                        placeholder="Contoh: {{ $item->nomor_rekening }}"
                                                                        value="{{ $item->nomor_rekening }}">
                                                                    @error('nomor_rekening')
                                                                        <span class="validated">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nama Pemilik Rekening
                                                                        Bank:</h1>
                                                                    <input required name="pemilik" type="text"
                                                                        placeholder="Contoh: {{ $item->pemilik }}"
                                                                        value="{{ $item->pemilik }}">
                                                                    @error('pemilik')
                                                                        <span class="validated">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan saldo Rekening
                                                                        Bank:</h1>
                                                                    <input required name="saldo_bank" type="number"
                                                                        placeholder="Contoh: {{ $item->saldo_bank }}"
                                                                        value="{{ $item->saldo_bank }}">
                                                                    @error('saldo_bank')
                                                                        <span class="validated">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-action">
                                                                <button type="button" class="btn"
                                                                    onclick="document.getElementById('update_bank_modal_{{ $item->id_bank }}').close()">Tutup</button>
                                                                <button type="submit"
                                                                    class="btn btn-success capitalize">Update
                                                                    Bank</button>
                                                            </div>
                                                        </form>
                                                    </dialog>

                                                    <x-lucide-trash-2
                                                        onclick="document.getElementById('delete_modal_{{ $item->id_bank }}').showModal();"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                    <dialog id="delete_modal_{{ $item->id_bank }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('delete.bank', ['id_bank' => $item->id_bank]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @csrf
                                                            @method('DELETE')
                                                            <h3 class="modal-title capitalize">Hapus bank</h3>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus bank
                                                                    "{{ $item->nama_bank }} - {{ $item->pemilik }}"?
                                                                </p>
                                                            </div>
                                                            <div class="modal-action">
                                                                <button type="button" class="btn"
                                                                    onclick="document.getElementById('delete_modal_{{ $item->id_bank }}').close()">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger capitalize">Hapus</button>
                                                            </div>
                                                        </form>
                                                    </dialog>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

@foreach (['tambah_bank'] as $item)
    @php
        $type = explode('_', $item)[1];
        $route = 'store.' . $type;
    @endphp
    <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
        <form action="{{ route($route) }}" method="POST" class="modal-box bg-secondary">
            @csrf
            <h3 class="modal-title capitalize">
                {{ str_replace('_', ' ', $item) }}
            </h3>
            <div class="modal-body">
                <div class="input-label">
                    <h1 class="label">Masukkan Nama {{ ucfirst($type) }}:</h1>
                    <input required name="{{ $type }}" type="text" placeholder="Contoh: BRI">
                    @error($type)
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nomor Rekening {{ ucfirst($type) }}:</h1>
                    <input required name="nomor_rekening" type="number" placeholder="Contoh: 12345678">
                    @error($type)
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nama Pemilik Rekening {{ ucfirst($type) }}:</h1>
                    <input required name="pemilik" type="text" placeholder="Contoh: Yantoo">
                    @error($type)
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan saldo Rekening {{ ucfirst($type) }}:</h1>
                    <input required name="saldo_bank" type="number" placeholder="Contoh: 100000">
                    @error($type)
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('{{ $item }}_modal').close()">Tutup</button>
                <button type="submit" class="btn btn-success capitalize">Tambah {{ ucfirst($type) }}</button>
            </div>
        </form>
    </dialog>
@endforeach
