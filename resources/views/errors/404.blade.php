<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Absensi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            margin: auto;
            max-width: 500px;
        }
        .btn {
            font-size: 14pt;
            padding: 1rem;
        }
        .clock {
            font-family: 'Arial', sans-serif;
            color: #333;
            font-size: 24px;
            text-align: center;
            width: 300px;
            margin: 50px auto;
        }
        .responsive-img {
            max-width: 50%;
            height: auto;
        }
        .tiny-font{
            font-size : 10pt;
        }
    </style>
</head>
<body>
<div class="container">
        <h1>Halaman Tidak Ada</h1>
        <p>Maaf, halaman yang Anda cari tidak ditemukan.</p>
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </div>
</body>
</html>

