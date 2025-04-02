<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <h3 class="text-xl">Selamat datang, {{ $user['username'] }}</h3>
  <p>{{ $message }}</p>
</x-layout>