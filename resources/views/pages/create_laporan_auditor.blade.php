<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Audit untuk: ') }} {{ $audit->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    <form action="{{ route('pelaporan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="audit_id" value="{{ $audit->id }}">

                        <!-- Judul Laporan -->
                        <div class="mb-4">
                            <label for="report_title" class="block text-sm font-medium text-gray-700 Laporan</label>
                            <input type="text" id="report_title" name="report_title" value="{{ old('report_title', 'Laporan Audit ' . $audit->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 required>
                        </div>

                        <!-- Ringkasan Eksekutif -->
                        <div class="mb-4">
                            <label for="executive_summary" class="block text-sm font-medium text-gray-700 Eksekutif</label>
                            <textarea id="executive_summary" name="executive_summary" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 old('executive_summary') }}</textarea>
                        </div>

                        <!-- Temuan dan Rekomendasi -->
                        <div class="mb-4">
                            <label for="findings_recommendations" class="block text-sm font-medium text-gray-700 dan Rekomendasi</label>
                            <textarea id="findings_recommendations" name="findings_recommendations" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 old('findings_recommendations') }}</textarea>
                        </div>

                        <!-- Skor Kepatuhan -->
                        <div class="mb-4">
                            <label for="compliance_score" class="block text-sm font-medium text-gray-700 Kepatuhan (%)</label>
                            <input type="number" id="compliance_score" name="compliance_score" value="{{ old('compliance_score') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 min="0" max="100" step="0.01">
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-md shadow-sm">
                                Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
