<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('admin.items.update', $item) }}">
                    @method('PUT')
                    @include('admin.items._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
