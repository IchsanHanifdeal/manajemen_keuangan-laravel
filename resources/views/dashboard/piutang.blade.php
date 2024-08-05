<x-dashboard.main title="Piutang">
    <div class="grid sm:grid-cols-2 gap-5 md:gap-6">
        @foreach (['total_piutang', 'piutang_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                  {{ $type == 'total_piutang' ? 'bg-blue-300' : '' }}
                  {{ $type == 'piutang_terbaru' ? 'bg-amber-300' : '' }}
                  p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p class="text-lg font-semibold line-clamp-1">
                        {{ $type == 'total_piutang' ? 'Rp ' . number_format($total_piutang ?? 0, 0, ',', '.') : '' }}
                        {{ $type == 'piutang_terbaru' ? $piutang_terbaru->piutang_keterangan ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_piutang'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize ">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60 ">
                        {{ $item == 'tambah_piutang' ? 'Menambahkan piutang' : '' }}
                    </p>
                </div>
                <x-lucide-plus class="{{ $item == 'tambah_piutang' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60 " />
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['manajemen_piutang'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Buat identitas setiap piutang dengan jelas.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach (['no', 'tanggal piutang', 'nominal piutang', 'keterangan piutang'] as $item)
                                        <th class="uppercase font-bold text-center">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($piutang->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center font-semibold text-gray-500">Tidak ada
                                            piutang</td>
                                    </tr>
                                @else
                                    @foreach ($piutang as $i => $item)
                                        <tr class="text-center">
                                            <th>{{ $i + 1 }}</th>
                                            <td class="font-semibold uppercase">{{ $item->piutang_tanggal }}</td>
                                            <td class="font-semibold">
                                                Rp{{ number_format($item->piutang_nominal, 0, ',', '.') }}
                                            </td>
                                            <td class="font-semibold uppercase">{{ $item->piutang_keterangan }}</td>
                                            @if (session()->get('role') !== 'user')
                                                <td class="flex items-center gap-4">
                                                    <x-lucide-square-pen
                                                        onclick="update_piutang_modal_{{ $item->id_piutang }}.showModal();initUpdate('bank', {{ $item }})"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />

                                                    <dialog id="update_piutang_modal_{{ $item->id_piutang }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('update.piutang', ['id_piutang' => $item->id_piutang]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @csrf
                                                            @method('PUT')
                                                            <h3 class="modal-title capitalize">
                                                                Update piutang tanggal {{ $item->piutang_tanggal }}
                                                            </h3>
                                                            <div class="modal-body">
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Tanggal piutang:
                                                                    </h1>
                                                                    <input required name="piutang_tanggal"
                                                                        type="date"
                                                                        value="{{ $item->piutang_tanggal }}">
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nominal piutang:
                                                                    </h1>
                                                                    <input required name="piutang_nominal"
                                                                        type="number"
                                                                        value="{{ $item->piutang_nominal }}">
                                                                </div>
                                                                <div class="input-label">
                                                                    <h1 class="label">Masukkan Nominal piutang:
                                                                    </h1>
                                                                    <textarea required name="piutang_keterangan" class="textarea textarea-bordered border-white">{{ $item->piutang_keterangan }}</textarea>
                                                                </div>
                                                                <div class="modal-action">
                                                                    <button type="button" class="btn"
                                                                        onclick="document.getElementById('update_piutang_modal_{{ $item->id_piutang }}').close()">Tutup</button>
                                                                    <button type="submit"
                                                                        class="btn btn-success capitalize">Update
                                                                        piutang</button>
                                                                </div>
                                                        </form>
                                                    </dialog>

                                                    <x-lucide-trash-2
                                                        onclick="document.getElementById('delete_modal_{{ $item->id_piutang }}').showModal();"
                                                        class="size-5 hover:stroke-blue-500 cursor-pointer" />
                                                    <dialog id="delete_modal_{{ $item->id_piutang }}"
                                                        class="modal modal-bottom sm:modal-middle">
                                                        <form
                                                            action="{{ route('delete.piutang', ['id_piutang' => $item->id_piutang]) }}"
                                                            method="POST" class="modal-box bg-secondary">
                                                            @csrf
                                                            @method('DELETE')
                                                            <h3 class="modal-title capitalize">Hapus piutang</h3>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus piutang tanggal
                                                                    "{{ $item->piutang_tanggal }}"?</p>
                                                            </div>
                                                            <div class="modal-action">
                                                                <button type="button" class="btn"
                                                                    onclick="document.getElementById('delete_modal_{{ $item->id_piutang }}').close()">Batal</button>
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

@foreach (['tambah_piutang'] as $item)
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
                    <h1 class="label">Masukkan Tanggal piutang:
                    </h1>
                    <input required name="piutang_tanggal" type="date">
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nominal piutang:
                    </h1>
                    <input required name="piutang_nominal" type="number">
                </div>
                <div class="input-label">
                    <h1 class="label">Masukkan Nominal piutang:
                    </h1>
                    <textarea required name="piutang_keterangan" class="textarea textarea-bordered border-white"></textarea>
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
