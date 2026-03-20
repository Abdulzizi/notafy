<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') &mdash; Notafy — OCR Nota Otomatis</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white border rounded-lg p-6">
        @yield('content')
    </div>
</body>

</html>
