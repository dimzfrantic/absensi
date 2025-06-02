@section('title', 'Absensi QR Code')
@extends('layouts.app')

@section('content')
<style>
    .absensi-container {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    #reader {
        margin: 0 auto;
    }
    #log-absensi th, #log-absensi td {
        font-size: 14px;
    }
</style>

<div class="container absensi-container">
    <h1 class="mb-4">Absensi Kegiatan</h1>

    <div class="form-group mb-3 w-100" style="max-width: 400px;">
        <label for="kegiatan">Pilih Kegiatan:</label>
        <select class="form-control" id="kegiatan">
            <option value="">-- Pilih Kegiatan --</option>
            @foreach($kegiatans as $kegiatan)
                <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }} ({{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }})</option>
            @endforeach
        </select>
    </div>

    <button id="start-scan" class="btn btn-primary mb-3">Mulai Scan QR Code</button>

    <div id="reader" style="width: 300px; display: none;"></div>

    <div id="result" class="mt-3" style="max-width: 400px; margin: 0 auto;"></div>
    <!-- Audio untuk notifikasi sukses -->
    <audio id="ding-audio" src="/sounds/ding.mp3" preload="auto"></audio>
    
    <div class="mt-4 w-100" style="max-width:600px;">
        <h4>Daftar Absensi Pegawai Hari Ini</h4>
        <table class="table table-striped" id="log-absensi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Divisi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <!-- data log realtime di-inject via JS -->
            </tbody>
        </table>
    </div>

</div>
{{-- Footer --}}
<footer class="text-center mt-5 mb-2 text-muted" style="font-size: 14px;">
    &copy; TI Kanwil Kemenkum Jawa Barat 2025
</footer>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
    let html5QrCode;
    const resultContainer = document.getElementById('result');

    function startScanner() {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 200 },
            (decodedText, decodedResult) => {
                const kegiatanId = document.getElementById('kegiatan').value;

                if (kegiatanId === "") {
                    resultContainer.innerHTML = `<div class="alert alert-warning">Pilih kegiatan terlebih dahulu.</div>`;
                    return;
                }

                html5QrCode.stop().then(() => {
                    $.ajax({
                        url: "{{ route('absensi.scan') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nip: decodedText,
                            kegiatan_id: kegiatanId
                        },
                        success: function(response) {
                            let alertClass = 'success';
                            if (response.status === 'error') alertClass = 'danger';
                            if (response.status === 'info') alertClass = 'info';

                            resultContainer.innerHTML = `<div class="alert alert-${alertClass}">${response.message}</div>`;

                            if (response.status !== 'error') {
                                document.getElementById('ding-audio').play();
                            }

                            setTimeout(() => {
                                resultContainer.innerHTML = "";
                                startScanner();
                            }, 1500);
                        },

                        error: function(xhr) {
                            let message = "QR Code Tidak Valid.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            resultContainer.innerHTML = `<div class="alert alert-danger">${message}</div>`;

                            setTimeout(() => {
                                resultContainer.innerHTML = "";
                                startScanner();
                            }, 1500);
                        }
                    });
                });
            },
            (errorMessage) => {
                // Optional: console.log(errorMessage);
            }
        ).catch(err => {
            resultContainer.innerHTML = `<div class="alert alert-danger">Gagal mengakses kamera: ${err}</div>`;
        });
    }

    document.getElementById('start-scan').addEventListener('click', () => {
        const kegiatanId = document.getElementById('kegiatan').value;

        if (kegiatanId === "") {
            resultContainer.innerHTML = `<div class="alert alert-warning">Pilih kegiatan terlebih dahulu.</div>`;
            return;
        }
        // Hapus pesan error jika ada, saat mulai scan
        resultContainer.innerHTML = "";
        document.getElementById('reader').style.display = 'block';
        startScanner();
    });

    // Hapus pesan error jika user memilih kegiatan setelah error muncul
    document.getElementById('kegiatan').addEventListener('change', function() {
        if (this.value !== "") {
            resultContainer.innerHTML = "";
        }
        loadLog();
    });
    function loadLog() {
        const kegiatanId = document.getElementById('kegiatan').value;
        if (kegiatanId === "") return;

        $.ajax({
            url: "/absensi/log/" + kegiatanId,
            method: "GET",
            success: function(response) {
                let tbody = '';
                response.forEach((absen, index) => {
                    tbody += `<tr>
                        <td>${index + 1}</td>
                        <td>${absen.pegawai.nama}</td>
                        <td>${absen.pegawai.nip}</td>
                        <td>${absen.pegawai.divisi ?? '-'}</td>
                        <td>${moment(absen.created_at).format('DD-MM-YYYY HH:mm:ss')}</td>
                    </tr>`;
                });
                $('#log-absensi tbody').html(tbody);
            }
        });
    }

    setInterval(loadLog, 3000);
    document.getElementById('kegiatan').addEventListener('change', loadLog);
</script>
@endsection
