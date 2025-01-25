<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi | Gunung Sari Sedana</title>
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
            width: 100%;
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
    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
        <div class="container p-5 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="" class="responsive-img mb-2">
            <h5>{{ $profil->nama_perusahaan }}</h5>
            <h6>{{ $profil->badan_hukum }}</h6>
            <span class="tiny-font">{{ $profil->alamat }} <br> {{ $profil->no_telp }}</span>
            <br><br>
            <div class="card">
                <div class="card-body text-center">
                    <h5>{{ $karyawan->nama }}</h5>
                    <span class="tiny-font">{{ $karyawan->jabatan->nama_jabatan }}</span>
                    
                    <!-- Animasi Jam -->
                    <h6>{{ date('D, d M Y') }}</h6>
                    <div id="clock" class="clock mt-4 mb-4 align-items-center"></div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($profil->status == 'aktif')
                    <form id="absenForm" action="{{ route('absen.handle.nomor_anggota', ['nomor_anggota' => $karyawan->nomor_anggota]) }}" method="POST">
                        @csrf
                        @if(!$absensi)
                            <button type="submit" id="absenButton" class="btn btn-success btn-sm btn-block" onclick="checkLocationAndSubmit(event)">Absen Masuk</button>
                        @elseif($absensi && !$absensi->jam_keluar)
                            <button type="submit" id="absenButton" class="btn btn-danger btn-sm btn-block" onclick="checkLocationAndSubmit(event)">Absen Keluar</button>
                        @else
                            <button type="button" class="btn btn-warning btn-sm btn-block" disabled>Sudah Absen</button>
                        @endif
                    </form>
                    @else
                    <form action="{{ route('absen.handle.nomor_anggota', ['nomor_anggota' => $karyawan->nomor_anggota]) }}" method="POST">
                        @csrf
                        @if(!$absensi)
                            <button type="submit" id="absenButton" class="btn btn-success btn-sm btn-block">Absen Masuk</button>
                        @elseif($absensi && !$absensi->jam_keluar)
                            <button type="submit" id="absenButton" class="btn btn-danger btn-sm btn-block">Absen Keluar</button>
                        @else
                            <button type="button" class="btn btn-warning btn-sm btn-block" disabled>Sudah Absen</button>
                        @endif
                    </form>
                    @endif
                    @if($absensi)
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Jumlah Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $absensi->jam_masuk }}</td>
                                    <td>{{ $absensi->jam_keluar }}</td>
                                    <td>{{ $absensi->jumlah_jam_kerja }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
    $serverTime = new DateTime();
    $serverTime->setTimezone(new DateTimeZone('Asia/Makassar')); 
    @endphp
    <script>
        function checkLocationAndSubmit(event) {
            event.preventDefault(); // Mencegah form terkirim secara otomatis

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            console.log('Latitude:', latitude, 'Longitude:', longitude); // Log posisi untuk memastikan

            fetch('/check-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude
                })
            }).then(response => response.json())
            .then(data => {
                console.log('Response from server:', data); // Log respons untuk memastikan
                if (data.access) {
                    // Jika akses diberikan, kirim form
                    document.getElementById('absenForm').submit();
                } else {
                    alert("Anda berada di luar area Kantor!");
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

       // Mendapatkan waktu server dari PHP
        let serverHour = <?php echo $serverTime->format('H'); ?>;
        let serverMinute = <?php echo $serverTime->format('i'); ?>;
        let serverSecond = <?php echo $serverTime->format('s'); ?>;

        function updateClock() {
            // Incrementing the time by one second
            serverSecond++;
            if (serverSecond === 60) {
                serverSecond = 0;
                serverMinute++;
                if (serverMinute === 60) {
                    serverMinute = 0;
                    serverHour++;
                    if (serverHour === 24) {
                        serverHour = 0;
                    }
                }
            }

            // Formatting the time to display
            const formattedHour = String(serverHour).padStart(2, '0');
            const formattedMinute = String(serverMinute).padStart(2, '0');
            const formattedSecond = String(serverSecond).padStart(2, '0');

            // Displaying the time
            const clockDiv = document.getElementById('clock');
            clockDiv.innerHTML = `${formattedHour}:${formattedMinute}:${formattedSecond}`;
        }

        // Initial call to set the clock immediately
        updateClock();
        // Updating the clock every second
        setInterval(updateClock, 1000);
    </script>
</body>
</html>

