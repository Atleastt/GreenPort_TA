<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Report Generator -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Laporan Audit Baru</h1>
                                        @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <form action="{{ route('pelaporan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        @csrf
                        <div>
                            <label for="audit_name" class="block text-sm font-medium text-gray-700">Nama Audit</label>
                            <select id="audit_name" name="audit_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @foreach($audit as $a)
                                    <option value="{{$a->id}}">{{$a->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700">Jenis Laporan</label>
                            <select id="report_type" name="report_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                <option>Ringkasan Eksekutif</option>
                                <option>Laporan Temuan Rinci</option>
                                <option>Laporan Tindak Lanjut</option>
                            </select>
                        </div>
                        {{-- <div>
                            <label for="report-title" class="block text-sm font-medium text-gray-700">Judul Laporan</label>
                            <input type="text" name="report-title" id="report-title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Tambahkan judul laporan">
                        </div> --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Periode Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Periode Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150 h-10">
                                Buat Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Available Reports Table -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Tersedia</h1>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Laporan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->name || "" }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at->isoFormat('D MMMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->period_start->isoFormat('D MMM YYYY') }} - {{ $report->period_end->isoFormat('D MMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Unduh</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Laporan</h3>
                                            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat laporan baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
