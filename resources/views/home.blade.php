@extends('layouts.app')

@section('title', 'Home - Sistem Sumber Plastik Packing')

@section('content')
<div class="text-center py-12">
    <h1 class="text-5xl font-bold mb-12" style="font-family: 'Playfair Display', serif;">
        Selamat Datang<br>
        di Sistem Sumber Plastik Packing
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mt-16">
        <!-- Admin Card -->
        <div class="bg-gray-300 rounded-3xl p-8 hover:shadow-lg transition">
            <div class="text-6xl mb-6">👤</div>
            <h2 class="text-3xl font-bold mb-4">Admin</h2>
            <p class="text-gray-700 mb-8">Masukkan pesanan baru customer</p>
            <a href="{{ route('login') }}" 
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition">
                CLIK HERE
            </a>
        </div>

        <!-- Gudang Card -->
        <div class="bg-gray-300 rounded-3xl p-8 hover:shadow-lg transition">
            <div class="text-6xl mb-6">🏢</div>
            <h2 class="text-3xl font-bold mb-4">Gudang</h2>
            <p class="text-gray-700 mb-8">Persiapkan pesanan dan Restock barang</p>
            <a href="{{ route('login') }}" 
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition">
                CLIK HERE
            </a>
        </div>
    </div>
</div>
@endsection
