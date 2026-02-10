<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kategori</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @method('PUT')
                    @include('admin.categories._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
