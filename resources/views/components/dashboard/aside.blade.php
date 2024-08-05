<div class="drawer-side border-r z-20">
    <label for="aside-dashboard" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul
        class="menu p-4 w-64 lg:w-72 min-h-full [&>li>a]:gap-4 [&>li]:my-1.5 [&>li]:text-[14.3px] [&>li]:font-medium [&>li]:text-opacity-80 [&>li]:text-base [&>_*_svg]:stroke-[1.5] [&>_*_svg]:size-[23px] [&>.label]:mt-6">
        <div class="pb-4 border-b border-gray-300">
            @include('components.brands', ['class' => '!text-2xl'])
        </div>
        <span class="label text-xs font-extrabold opacity-50">GENERAL</span>
        <li>
            <a href="{{ route('dashboard') }}" class="{!! Request::path() == 'dashboard' ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-bar-chart-2 />
                Dashboard
            </a>
        </li>
        @if (session()->get('role') !== 'user')
            <li>
                <a href="{{ route('kategori') }}" class="{!! preg_match('#^dashboard/kategori.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-arrow-up-down />
                    Kelola Kategori
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('bank') }}" class="{!! preg_match('#^dashboard/bank.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-credit-card />
                Kelola Rekening
            </a>
        </li>
        <li>
            <a href="{{ route('transaksi') }}" class="{!! preg_match('#^dashboard/transaksi.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-badge-cent />
                Kelola Transaksi
            </a>
        </li>
        <li>
            <a href="{{ route('hutang') }}" class="{!! preg_match('#^dashboard/hutang.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-receipt />
                Kelola Hutang
            </a>
        </li>
        <li>
            <a href="{{ route('piutang') }}" class="{!! preg_match('#^dashboard/piutang.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-receipt />
                Kelola Piutang
            </a>
        </li>
        @if (session()->get('role') !== 'user')
            <li>
                <a href="{{ route('pengguna') }}" class="{!! preg_match('#^dashboard/pengguna.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                    <x-lucide-users-2 />
                    Kelola Pengguna
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('laporan') }}" class="{!! preg_match('#^dashboard/laporan.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-clipboard />
                Laporan Keuangan
            </a>
        </li>
        <li>
            <a href="{{ route('profile') }}" class="{!! preg_match('#^dashboard/profile.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5">
                <x-lucide-circle-user />
                Profile
            </a>
        </li>
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-0">
                @csrf
                <a class="flex items-center px-2.5 gap-4" href="#"
                    onclick="event.preventDefault(); confirmLogout();">
                    <x-lucide-log-out />
                    Logout
                </a>
            </form>
        </li>
    </ul>
</div>
