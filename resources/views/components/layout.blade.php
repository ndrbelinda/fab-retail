<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>FAB Retail</title>
</head>
<body class="h-full font-jakarta">
    
  <div class="flex">
    {{-- Side bar --}}
    <x-navbar></x-navbar>

    <div class="flex-1 p-8">
      {{-- Header --}}
      <x-header></x-header>

      {{-- Content --}}
      <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>
        {{ $slot }}
      </div>
    </div>
  </div>

</body>
</html>