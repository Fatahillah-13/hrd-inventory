<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ATK Master - Order</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow">
                <form method="GET" class="flex flex-wrap gap-2 items-center">
                    <label>Status:</label>
                    <select name="status" class="border rounded px-3 py-2">
                        @foreach (['submitted', 'approved', 'rejected'] as $st)
                            <option value="{{ $st }}" @selected(($status ?? 'submitted') === $st)>{{ $st }}
                            </option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-900 text-white rounded">Filter</button>

                    <a href="{{ route('atk_master.recap') }}" class="px-4 py-2 border rounded">
                        Rekap
                    </a>
                </form>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <form method="POST" action="{{ route('atk_master.bulk_decision') }}" class="space-y-3">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-2 md:items-center">
                        <select name="action" id="action" class="border rounded px-3 py-2">
                            <option value="approve">approve</option>
                            <option value="reject">reject</option>
                        </select>

                        <input name="rejected_reason" id="rejected_reason"
                            placeholder="Alasan reject (wajib jika reject)" class="border rounded px-3 py-2 w-full" />

                        <button class="px-4 py-2 bg-gray-900 text-white rounded"
                            onclick="return confirm('Proses order terpilih?')">
                            Proses Terpilih
                        </button>
                    </div>

                    @error('order_ids')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                    @error('rejected_reason')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left p-3">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th class="text-left p-3">Order</th>
                                    <th class="text-left p-3">Divisi</th>
                                    <th class="text-left p-3">Requester</th>
                                    <th class="text-left p-3">Status</th>
                                    <th class="text-left p-3">Tanggal</th>
                                    <th class="text-left p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                    <tr class="border-t">
                                        <td class="p-3">
                                            <input class="chk" type="checkbox" name="order_ids[]"
                                                value="{{ $o->id }}">
                                        </td>
                                        <td class="p-3">#{{ $o->id }}</td>
                                        <td class="p-3">{{ $o->division->name }}</td>
                                        <td class="p-3">{{ $o->requester->name }}</td>
                                        <td class="p-3">{{ $o->status }}</td>
                                        <td class="p-3">{{ $o->created_at->format('d-m-Y H:i') }}</td>
                                        <td class="p-3">
                                            <a class="text-blue-600"
                                                href="{{ route('atk_master.orders.show', $o) }}">Proses</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="p-3" colspan="7">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>{{ $orders->links() }}</div>
                </form>
            </div>

        </div>
    </div>

    <script>
        const action = document.getElementById('action');
        const reason = document.getElementById('rejected_reason');
        const checkAll = document.getElementById('checkAll');

        function toggleReason() {
            const isReject = action.value === 'reject';
            reason.disabled = !isReject;
            reason.required = isReject;
            if (!isReject) reason.value = '';
        }

        action.addEventListener('change', toggleReason);
        toggleReason();

        checkAll?.addEventListener('change', function() {
            document.querySelectorAll('.chk').forEach(c => c.checked = checkAll.checked);
        });
    </script>
</x-app-layout>
