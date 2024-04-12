<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white">Products</h1>
                    @auth
                    <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Add Product
                    </a>
                    @endauth
                </div>
            </div>
            <div class="mt-4">
                <ul>
                    @foreach($products as $product)
                        <li class="bg-gray-100 dark:bg-gray-700 p-4 mb-4 rounded">
                            <span class="font-bold text-lg text-white">{{ $product->name }}</span> - <span class="text-gray-600 text-white">{{ $product->price }}</span>
                            <p class="text-sm text-white">{{ $product->description }}</p>
                            <img src="{{ asset('/assets/img/nophoto.jpg') }}" alt="No Photo" class="w-16 h-16">
                            @auth
                                <a href="{{ route('products.edit', $product->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-2 rounded ml-2 text-white">
                                    Edit
                                </a>
                            @endauth
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
