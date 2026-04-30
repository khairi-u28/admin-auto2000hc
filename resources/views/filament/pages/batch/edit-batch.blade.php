@extends('filament-panels::page')

@section('content')
<div class="space-y-6">
    <!-- Standard Filament edit form -->
    <form wire:submit.prevent="save" class="space-y-6">
        @php
            $schema = $this->getResource()::form($this->getForm());
            $schema->statePath('data');
        @endphp

        {{ $schema->toHtml() }}
    </form>

    @php
        $record = $this->getRecord();
        $participants = $record?->participants()->with('employee.branch')->get() ?? collect();
        $materi = $record?->materi()->with(['module', 'course'])->orderBy('order_index')->get() ?? collect();
    @endphp

    <!-- Tabs -->
    <div x-data="{ activeTab: 'silabus' }" class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    @click="activeTab = 'silabus'"
                    :class="activeTab === 'silabus' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Silabus
                </button>
                <button
                    @click="activeTab = 'peserta'"
                    :class="activeTab === 'peserta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Peserta
                </button>
                <button
                    @click="activeTab = 'feedback'"
                    :class="activeTab === 'feedback' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Feedback Peserta
                </button>
                <button
                    @click="activeTab = 'evaluasi'"
                    :class="activeTab === 'evaluasi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Evaluasi
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Silabus Tab -->
            <div x-show="activeTab === 'silabus'" x-transition>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Silabus Training</h3>
                @if($materi->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                        Belum ada silabus yang ditetapkan untuk batch ini.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($materi as $item)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">{{ $item->module?->title ?? 'Tanpa Modul' }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->course?->title ?? 'Tanpa Course' }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <p>{{ optional($item->session_datetime)->format('d M Y H:i') ?? '-' }}</p>
                                        <p>{{ $item->session_venue ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 text-sm text-gray-600">
                                    <p class="font-medium text-gray-700">Catatan:</p>
                                    <p>{{ $item->session_notes ?? 'Tidak ada catatan silabus.' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Peserta Tab -->
            <div x-show="activeTab === 'peserta'" x-transition>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Manajemen Peserta</h3>
                @if($participants->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                        Belum ada peserta ditambahkan untuk batch ini.
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Peserta</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kode Cabang</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($participants as $participant)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $participant->employee?->nama_lengkap ?? $participant->employee?->full_name ?? 'Nama tidak tersedia' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $participant->employee?->branch?->kode_cabang ?? $participant->employee?->branch?->code ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                                {{ $participant->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Feedback Tab -->
            <div x-show="activeTab === 'feedback'" x-transition>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback Peserta</h3>
                @if($participants->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                        Tambahkan peserta terlebih dahulu untuk melihat feedback.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($participants as $participant)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">{{ $participant->employee?->nama_lengkap ?? 'Nama tidak tersedia' }}</p>
                                        @if($participant->feedback)
                                            <p class="text-sm text-gray-500">Status: {{ $participant->feedback->is_submitted ? 'Terkirim' : 'Belum dikirim' }}</p>
                                        @else
                                            <p class="text-sm text-gray-500">Status: Belum ada feedback</p>
                                        @endif
                                    </div>
                                    @if($participant->feedback)
                                        <div class="text-sm text-gray-500 text-right">
                                            <p>Rating Training: {{ number_format($participant->feedback->training_avg, 1) }}/5</p>
                                            <p>Rating Trainer: {{ number_format($participant->feedback->trainer_avg, 1) }}/5</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Komentar Training</p>
                                        <p class="text-sm text-gray-600">{{ $participant->feedback?->training_comments ?? 'Tidak ada komentar training.' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Komentar Trainer</p>
                                        <p class="text-sm text-gray-600">{{ $participant->feedback?->trainer_comments ?? 'Tidak ada komentar trainer.' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Evaluasi Tab -->
            <div x-show="activeTab === 'evaluasi'" x-transition>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Evaluasi Batch</h3>
                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Status Batch saat ini: <span class="font-semibold text-gray-800">{{ $record?->status ?? '-' }}</span></p>
                        <p class="mt-2 text-sm text-gray-600">Gunakan tab formulir utama di atas untuk memperbarui properti umum batch. Jika batch sudah selesai, Anda dapat menambahkan evaluasi di bawah ini.</p>
                    </div>

                    @if($record?->status === 'selesai')
                        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <label class="block text-sm font-medium text-gray-700">Evaluasi Batch</label>
                            <textarea
                                wire:model.defer="data.evaluation"
                                rows="8"
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >{{ $record->evaluation }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">Perubahan akan disimpan saat Anda menekan tombol Simpan Perubahan.</p>
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                            Evaluasi hanya dapat diisi ketika status batch sudah berstatus selesai.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection