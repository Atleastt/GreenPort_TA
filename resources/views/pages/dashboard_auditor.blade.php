<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ deleteModalOpen: false, itemToDelete: { id: null, text: '' } }" class="space-y-8">

                <!-- Welcome Banner -->
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 Datang, Auditor!</h2>
                        <p class="mt-1 text-gray-600 adalah ringkasan aktivitas audit Anda.</p>
                    </div>
                    <div class="text-gray-500
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <h3 class="font-semibold text-lg mb-4 text-gray-800 Diaudit per Bulan</h3>
                        <div class="h-64">
                            <canvas id="auditorLineChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <h3 class="font-semibold text-lg mb-4 text-gray-800 Dokumen Audit</h3>
                        <div class="h-64 flex items-center justify-center">
                            <canvas id="auditorDoughnutChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Kriteria Chart Section -->
                <div class="bg-white p-6 rounded-2xl shadow-lg mt-8">
                    <h3 class="font-semibold text-lg mb-4 text-gray-800 Indikator per Kriteria</h3>
                    <div class="h-80">
                        <canvas id="kriteriaBarChart"></canvas>
                    </div>
                </div>

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md" role="alert">
                        <p class="font-bold">Berhasil!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Table Section -->
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-xl text-gray-800 Indikator Audit</h3>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('indikator.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150">
                                Tambah Indikator
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200
                            <thead class="bg-gray-50
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub-Kriteria</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200
                                @forelse ($indikators as $indikator)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 $indikator->subkriteria->kriteria->nama_kriteria ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 $indikator->subkriteria->nama_subkriteria ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-xs">{{ $indikator->teks_indikator }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 $indikator->bobot_indikator }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 $indikator->poin_maks_indikator }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('indikator.show', $indikator->id) }}" class="text-gray-400 hover:text-emerald-500 transition-colors" title="Lihat">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C3.732 4.943 9.522 3 10 3s6.268 1.943 9.542 7c-3.274 5.057-9.03 7-9.542 7S3.732 15.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                            </a>
                                            <a href="{{ route('indikator.edit', $indikator->id) }}" class="text-gray-400 hover:text-amber-500 transition-colors" title="Ubah">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                            </a>
                                            <button @click="deleteModalOpen = true; itemToDelete = { id: {{ $indikator->id }}, text: '{{ addslashes($indikator->teks_indikator) }}' }" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center py-12">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 Ada Indikator</h3>
                                            <p class="mt-1 text-sm text-gray-500 dengan menambahkan indikator audit baru.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <div x-show="deleteModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 id="modal-title">Hapus Indikator?</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 yakin ingin menghapus indikator "<strong x-text="itemToDelete.text"></strong>"? Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <form :action="'/indikator/' + itemToDelete.id" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Hapus
                                </button>
                            </form>
                            <button @click="deleteModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data from controller
            const chartData = JSON.parse(`{!! $chartData ?? 'null' !!}`);

            // Line Chart
            const lineCtx = document.getElementById('auditorLineChart');
            if (lineCtx) {
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Dokumen Diaudit',
                            data: [12, 19, 3, 5, 2, 3],
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // Doughnut Chart
            const doughnutCtx = document.getElementById('auditorDoughnutChart');
            if (doughnutCtx) {
                new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Proses', 'Revisi'],
                        datasets: [{
                            label: 'Status Dokumen',
                            data: [300, 50, 100],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // Bar Chart for Kriteria
            const kriteriaCtx = document.getElementById('kriteriaBarChart');
            if (kriteriaCtx && chartData) {
                new Chart(kriteriaCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Jumlah Indikator',
                            data: chartData.data,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
