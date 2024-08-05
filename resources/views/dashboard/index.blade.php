<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<x-dashboard.main title="Dashboard">
    @php
        $types = [
            'pemasukan_hari_ini' => [
                'label' => 'Pemasukan Hari Ini',
                'value' => $data['pemasukan_hari_ini'] ?? 0,
                'color' => 'bg-blue-300',
            ],
            'pemasukan_bulan_ini' => [
                'label' => 'Pemasukan Bulan Ini',
                'value' => $data['pemasukan_bulan_ini'] ?? 0,
                'color' => 'bg-green-300',
            ],
            'pemasukan_tahun_ini' => [
                'label' => 'Pemasukan Tahun Ini',
                'value' => $data['pemasukan_tahun_ini'] ?? 0,
                'color' => 'bg-rose-300',
            ],
            'seluruh_pemasukan' => [
                'label' => 'Seluruh Pemasukan',
                'value' => $data['seluruh_pemasukan'] ?? 0,
                'color' => 'bg-amber-300',
            ],
            'pengeluaran_hari_ini' => [
                'label' => 'Pengeluaran Hari Ini',
                'value' => $data['pengeluaran_hari_ini'] ?? 0,
                'color' => 'bg-blue-300',
            ],
            'pengeluaran_bulan_ini' => [
                'label' => 'Pengeluaran Bulan Ini',
                'value' => $data['pengeluaran_bulan_ini'] ?? 0,
                'color' => 'bg-green-300',
            ],
            'pengeluaran_tahun_ini' => [
                'label' => 'Pengeluaran Tahun Ini',
                'value' => $data['pengeluaran_tahun_ini'] ?? 0,
                'color' => 'bg-rose-300',
            ],
            'seluruh_pengeluaran' => [
                'label' => 'Seluruh Pengeluaran',
                'value' => $data['seluruh_pengeluaran'] ?? 0,
                'color' => 'bg-amber-300',
            ],
        ];
    @endphp

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-6">
        @foreach ($types as $type => $info)
            @php
                $formattedValue = 'Rp ' . number_format($info['value'], 0, ',', '.');
            @endphp
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span class="{{ $info['color'] }} p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600 line-clamp-1">
                        {{ $info['label'] }}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 line-clamp-1">
                        {{ $formattedValue }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">Grafik</h1>
            <div class="p-4 rounded-lg bg-white">
                <h2 class="text-xl font-semibold">Grafik Data Pemasukan & Pengeluaran Per <span
                        class="font-bold">Bulan</span></h2>
                <canvas id="monthlyChart" class="my-4"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg mt-8">
                <h2 class="text-xl font-semibold">Grafik Data Pemasukan & Pengeluaran Per <span
                        class="font-bold">Tahun</span></h2>
                <canvas id="yearlyChart" class="my-4"></canvas>
            </div>
        </div>
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">Kalender</h1>
            <div id="calendar" class="p-4 rounded-lg bg-white"></div>
        </div>
    </div>
</x-dashboard.main>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
        });

        calendar.render();
    });
</script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');

        const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const monthlyData = {
            labels: monthlyLabels,
            datasets: [{
                label: 'Pemasukan',
                data: @json($monthlyData->pluck('total_pemasukan')),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }, {
                label: 'Pengeluaran',
                data: @json($monthlyData->pluck('total_pengeluaran')),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            }]
        };

        const yearlyLabels = @json($yearlyData->pluck('year'));
        const yearlyData = {
            labels: yearlyLabels,
            datasets: [{
                label: 'Pemasukan',
                data: @json($yearlyData->pluck('total_pemasukan')),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }, {
                label: 'Pengeluaran',
                data: @json($yearlyData->pluck('total_pengeluaran')),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            }]
        };

        new Chart(monthlyCtx, {
            type: 'bar',
            data: monthlyData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        new Chart(yearlyCtx, {
            type: 'bar',
            data: yearlyData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    });
</script>