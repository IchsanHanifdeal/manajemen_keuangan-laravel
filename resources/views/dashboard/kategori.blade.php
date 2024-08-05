<x-dashboard.main title="Kategori">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_kategori', 'kategori_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_kategori' ? 'bg-blue-300' : '' }}
                  {{ $type == 'kategori_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold line-clamp-1">
                        {{ $type == 'total_kategori' ? $total_kategori : '' }}
                        {{ $type == 'kategori_terbaru' ? $kategori_terbaru->kategori ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_kategori'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize ">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60 ">
                        {{ $item == 'tambah_kategori' ? 'Menambahkan Kategori untuk pemasukan maupun pengeluaran' : '' }}
                    </p>
                </div>
                <x-lucide-plus class="{{ $item == 'tambah_kategori' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60 " />
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['manajemen_kategori'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Buat identitas setiap transaksi dengan menambahkan kategori.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach (['no', 'kategori'] as $item)
                                        <th class="uppercase font-bold text-center">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($kategori->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center font-semibold text-gray-500">Tidak ada
                                            kategori tersedia</td>
                                    </tr>
                                @else
                                    @foreach ($kategori as $i => $item)
                                        <tr class="text-center">
                                            <th>{{ $i + 1 }}</th>
                                            <td class="font-semibold uppercase">{{ $item->kategori }}</td>
                                            <td class="flex items-center gap-4">
                                                <x-lucide-square-pen
                                                    onclick="update_kategori_modal_{{ $item->id_kategori }}.showModal();initUpdate('kategori', {{ $item }})"
                                                    class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                <dialog id="update_kategori_modal_{{ $item->id_kategori }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <form method="POST" class="modal-box bg-secondary"
                                                        action="{{ route('update.kategori', ['id_kategori' => $item->id_kategori]) }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <h3 class="modal-title capitalize">
                                                            Update Kategori {{ $item->kategori }}
                                                        </h3>
                                                        <div class="modal-body">
                                                            <div class="input-label">
                                                                <h1 class="label">Masukan Nama Kategori:</h1>
                                                                <input required id="Kategori" name="kategori"
                                                                    type="text"
                                                                    placeholder="Contoh: {{ $item->kategori }}"
                                                                    value="{{ $item->kategori }}">
                                                                @error('kategori')
                                                                    <span class="validated">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button
                                                                onclick="update_kategori_modal_{{ $item->id_kategori }}.close()"
                                                                class="btn" type="button">Tutup</button>
                                                            <button type="submit" class="btn btn-success capitalize">
                                                                Update Kategori
                                                            </button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                                <x-lucide-trash-2
                                                    onclick="document.getElementById('delete_modal_{{ $item->id_kategori }}').showModal();"
                                                    class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                <dialog id="delete_modal_{{ $item->id_kategori }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <form
                                                        action="{{ route('delete.kategori', ['id_kategori' => $item->id_kategori]) }}"
                                                        method="POST" class="modal-box bg-secondary">
                                                        @csrf
                                                        @method('DELETE')
                                                        <h3 class="modal-title capitalize">Hapus Kategori</h3>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus kategori
                                                                "{{ $item->kategori }}"?</p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button" class="btn"
                                                                onclick="document.getElementById('delete_modal_{{ $item->id_kategori }}').close()">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-danger capitalize">Hapus</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            </td>
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

@foreach (['tambah_kategori'] as $item)
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
                    <input required name="{{ $type }}" type="text"
                        placeholder="Contoh: {{ $item == 'kategori' ? 'Contoh Kategori' : 'Kerja' }}">
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