<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABSENSI APP</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tambahan CSS untuk penyesuaian */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            width: 100%;
        }
        .clock {
            font-size: 2rem;
            margin: 20px 0;
            text-align: center;
        }
        .employee-info {
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ABSENSI APP</h1>
    </div>

    <!-- Animasi Jam -->
    <div class="container clock" id="clock">
        <!-- Jam akan ditampilkan di sini -->
    </div>

    <!-- Informasi Karyawan -->
    <div class="container employee-info">
        <h2>Nama Karyawan: John Doe</h2>
        <h3>Jabatan: Developer</h3>
        <form action="{{ route('absen.handle') }}" method="POST">
                @csrf
                @if(!$absensi)
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Absen Masuk</button>
                @elseif($absensi && !$absensi->jam_keluar)
                    <button type="submit" class="btn btn-warning btn-lg btn-block">Absen Keluar</button>
                @else
                    <button type="button" class="btn btn-success btn-lg btn-block" disabled>Sudah Absen</button>
                @endif
            </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 ABSENSI APP</p>
    </div>

    <!-- Bootstrap JS dan dependensi -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript untuk Animasi Jam -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').innerHTML = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock(); // panggil fungsi sekali untuk menginisialisasi jam
    </script>
</body>
</html>
