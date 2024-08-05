<x-dashboard.main title="Hutang">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_hutang', 'hutang_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_hutang' ? 'bg-blue-300' : '' }}
                  {{ $type == 'hutang_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold line-clamp-1">
                        {{ $type == 'total_hutang' ? 'Rp ' . number_format($total_hutang ?? 0, 0, ',', '.') : '' }}
                        {{ $type == 'hutang_terbaru' ? $hutang_terbaru->hutang_keterangan ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_hutang'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize ">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60 ">
                        {{ $item == 'tambah_hutang' ? 'Menambahkan Hutang' : '' }}
                    </p>
                </div>
                <x-lucide-plus class="{{ $item == 'tambah_hutang' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60 " />
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['manajemen_hutang'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Buat identitas setiap hutang dengan jelas.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach (['no', 'tanggal hutang', 'nominal hutang', 'keterangan hutang'] as $item)
                                        <th class="uppercase font-bold text-center">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hutang->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center font-semibold text-gray-500">Tidak ada
                                            hutang</td>
                                    </tr>
                                @else
                                    @foreach ($hutang as $i => $item)
                                        <tr class="text-center">
                                            <th>{{ $i + 1 }}</th>
                                            <td class="font-semibold uppercase">{{ $item->hutang_tanggal }}</td>
                                            <td class="font-semibold">
                                                Rp{{ number_format($item->hutang_nominal, 0, ',', '.') }}
                                            </td>
                                            <td class="font-semibold uppercase">{{ $item->hutang_keterangan }}</td>
                                            @if (session()->get('role') !== 'user')
                                                <td class="flex items-center gap-4">
                                                    <x-lucide-square-pen
                                                        onclick="update_hutang_modal_{{ $item->id_hutang }}.showModal();initUpdate('bank', {{ $item }})"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                    <dialog id="update_hutang_modal_{{ $item->id_hutang }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('update.hutang', ['id_hutang' => $item->id_hutang]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @csrf
                                                            @method('PUT')
                                                            <h3 class="modal-title capitalize">
                                                                Update Hutang tanggal {{ $item->hutang_tanggal }}
                                                            </h3>
                                                            <div class="modal-body">
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Tanggal hutang:
                                                                    </h1>
                                                                    <input required name="hutang_tanggal" type="date"
                                                                        value="{{ $item->hutang_tanggal }}">
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nominal hutang:
                                                                    </h1>
                                                                    <input required name="hutang_nominal" type="number"
                                                                        value="{{ $item->hutang_nominal }}">
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nominal hutang:
                                                                    </h1>
                                                                    <textarea required name="hutang_keterangan" class="textarea textarea-bordered border-white">{{ $item->hutang_keterangan }}</textarea>
                                                                </div>
                                                                <div class="modal-action">
                                                                    <button type="button" class="btn"
                                                                        onclick="document.getElementById('update_hutang_modal_{{ $item->id_hutang }}').close()">Tutup</button>
                                                                    <button type="submit"
                                                                        class="btn btn-success capitalize">Update
                                                                        Hutang</button>
                                                                </div>
                                                        </form>
                                                    </dialog>

                                                    <x-lucide-trash-2
                                                        onclick="document.getElementById('delete_modal_{{ $item->id_hutang }}').showModal();"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />
                                                    <dialog id="delete_modal_{{ $item->id_hutang }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('delete.hutang', ['id_hutang' => $item->id_hutang]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @csrf
                                                            @method('DELETE')
                                                            <h3 class="modal-title capitalize">Hapus hutang</h3>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus hutang tanggal
                                                                    "{{ $item->hutang_tanggal }}"?</p>
                                                            </div>
                                                            <div class="modal-action">
                                                                <button type="button" class="btn"
                                                                    onclick="document.getElementById('delete_modal_{{ $item->id_hutang }}').close()">Batal</button>
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

@foreach (['tambah_hutang'] as $item)
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
                    <h1 class="label">Masukkan Tanggal hutang:
                    </h1>
                    <input required name="hutang_tanggal" type="date">
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nominal hutang:
                    </h1>
                    <input required name="hutang_nominal" type="number">
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nominal hutang:
                    </h1>
                    <textarea required name="hutang_keterangan" class="textarea textarea-bordered border-white"></textarea>
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
