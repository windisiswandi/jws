<?php
    include 'koneksi.php';
    $setting = $conn->query("SELECT * FROM setting")->fetch_assoc();
    $kas_awal = @$setting['kas_awal_masjid'] ? $setting['kas_awal_masjid'] : 0;
    $kas_masuk = @$setting['kas_masuk_masjid'] ? $setting['kas_masuk_masjid'] : 0;
    $kas_keluar = @$setting['kas_keluar_masjid'] ? $setting['kas_keluar_masjid'] : 0;
    $total_kas = ($kas_awal + $kas_masuk) - $kas_keluar;

    $lokasi = $conn->query("SELECT * FROM lokasi")->fetch_assoc();
    if ($lokasi) {
        $latitude = $lokasi['latitude'];
        $longitude = $lokasi['longitude'];
        $city = $lokasi['city'];
        $type_lokasi = $lokasi['type'];
    }

    // var_dump($setting);
    // die;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Jadwal Sholat Masjid</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body class="bg-gray-100 tablet:p-6 font-sans">
    <a href="index.php" class="fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
        🔙 Kembali ke Jadwal
    </a>

    <div class="lg:max-w-[800px] mx-auto bg-white p-6 rounded-lg shadow-md lg:!text-lg">
        <h2 class="text-center text-2xl font-semibold mb-6">Admin Panel - Jadwal Sholat Masjid</h2>

        <!-- Atur Nama Masjid -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Atur Nama Masjid</h4>
            <input type="text" id="nama-masjid" class="w-full p-2 border rounded" placeholder="Masukkan nama masjid..." value="<?= @$setting['nama_masjid'] ?>">
            <button class="mt-2 w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700" onclick="saveNamaMasjid()">Simpan Nama Masjid</button>
        </div>

        <!-- Atur Lokasi Masjid -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Atur Lokasi Masjid</h4>
            <button class="w-full bg-blue-500 text-white p-2 rounded mb-2 hover:bg-blue-700" onclick="setLocationWithGPS()">📍 Gunakan Lokasi GPS</button>
            <input type="text" id="manual-lokasi" class="w-full p-2 border rounded" placeholder="Atau masukkan nama lokasi secara manual" value="<?= @$city; ?>">
            <button class="mt-2 w-full bg-green-500 text-white p-2 rounded hover:bg-green-700" onclick="saveManualLocation()">Simpan Lokasi Manual</button>
            <p id="location-status" class="text-gray-700 mt-2">
                <?php if(@$type_lokasi == "gps") : ?>
                    Lokasi GPS disimpan: Lat <?= $latitude; ?>, Long <?= $longitude; ?>
                <?php elseif(@$type_lokasi == "manual") : ?>
                    Lokasi Manual disimpan: <?= $city; ?>
                <?php else: ?>
                    Status Lokasi: 
                <?php endif; ?>
            </p>
        </div>

        <!-- Teks Berjalan -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Atur Teks Berjalan</h4>
            <textarea id="teks-berjalan" class="w-full p-2 border rounded" rows="3" placeholder="Masukkan teks berjalan..."><?= @$setting['text_berjalan']; ?></textarea>
            <button class="mt-2 w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700" onclick="saveTeksBerjalan()">Simpan Teks</button>
        </div>

        <!-- Informasi Kas -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Informasi Kas Masjid</h4>
            <input type="text" id="kas-awal" class="w-full p-2 border rounded mb-2" placeholder="Kas Awal" onkeyup="handlePrice(event)" value="<?= $kas_awal > 0 ? 'Rp '.number_format($kas_awal, 0, ",", ".") : '' ?>">
            <input type="text" id="kas-masuk" class="w-full p-2 border rounded mb-2" placeholder="Kas Masuk" onkeyup="handlePrice(event)" value="<?= $kas_masuk > 0 ? 'Rp '.number_format($kas_masuk, 0, ",", ".") : '' ?>">
            <input type="text" id="kas-keluar" class="w-full p-2 border rounded" placeholder="Kas Keluar" onkeyup="handlePrice(event)" value="<?= $kas_keluar > 0 ? 'Rp '.number_format($kas_keluar, 0, ",", ".") : '' ?>">
            <button class="mt-2 w-full bg-green-500 text-white p-2 rounded hover:bg-green-700"  onclick="saveKasMasjid()">Simpan Kas</button>
        </div>

        <!-- Upload Audio Tahrim -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Upload Audio Tahrim</h4>
            <input type="file" id="audio-tahrim" accept="audio/*" class="w-full p-2 border rounded">
            <button class="mt-2 w-full bg-yellow-500 text-white p-2 rounded hover:bg-yellow-700" onclick="uploadTahrimAudio()">Upload Tahrim</button>
        </div>

        <!-- Pengaturan Waktu Tahrim -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Pengaturan Waktu Tahrim</h4>
            <input type="number" id="waktu-tahrim" class="w-full p-2 border rounded" placeholder="Menit sebelum azan (contoh: 6)" value="<?= @$setting['waktu_tahrim'] ?>">
            <button class="mt-2 w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700" onclick="saveWaktuTahrim()">Simpan Waktu Tahrim</button>
        </div>

        <!-- Upload Audio Murottal -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Upload Audio Murottal</h4>
            <input type="file" id="audio-murottal" accept="audio/*" class="w-full p-2 border rounded">
            <button class="mt-2 w-full bg-yellow-500 text-white p-2 rounded hover:bg-yellow-700" onclick="uploadMurottalAudio()">Upload Murottal</button>
        </div>

        <!-- Pengaturan Waktu Murottal -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Pengaturan Waktu Murottal</h4>
            <input type="number" id="waktu-murottal" class="w-full p-2 border rounded" placeholder="Menit sebelum azan (contoh: 10)" value="<?= @$setting['waktu_murottal'] ?>">
            <button class="mt-2 w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700" onclick="saveWaktuMurottal()">Simpan Waktu Murottal</button>
        </div>
    </div>

    <script>
        // 🔊 Fungsi Upload Audio Murottal
        function uploadMurottalAudio() {
            const fileInput = document.getElementById('audio-murottal');
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const audioData = e.target.result;
                    localStorage.setItem('audioMurottal', audioData);
                    alert('Audio murottal berhasil disimpan!');
                };
                reader.readAsDataURL(file);
            } else {
                alert('Silakan pilih file audio murottal terlebih dahulu.');
            }
        }

        // ⏰ Simpan Waktu Murottal
        function saveWaktuMurottal() {
            const waktu = parseInt(document.getElementById('waktu-murottal').value);
            if (!isNaN(waktu) && waktu > 0) {
                const formData = new URLSearchParams();
                formData.append("waktu_murottal", waktu);

                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString(), // Kirim dalam format URL-encoded
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // console.log(data);
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
            } else {
                alert('Masukkan waktu yang valid sebelum azan.');
            }
        }

        // 🔊 Fungsi Upload Audio Tahrim
        function uploadTahrimAudio() {
            const fileInput = document.getElementById('audio-tahrim');
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const audioData = e.target.result;
                    localStorage.setItem('audioTahrim', audioData);
                    alert('Audio tahrim berhasil disimpan!');
                };
                reader.readAsDataURL(file);
            } else {
                alert('Silakan pilih file audio terlebih dahulu.');
            }
        }

        // ⏰ Simpan Waktu Tahrim
        function saveWaktuTahrim() {
            const waktu = parseInt(document.getElementById('waktu-tahrim').value);
            if (!isNaN(waktu) && waktu > 0) {
                const formData = new URLSearchParams();
                formData.append("waktu_tahrim", waktu);

                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString(), // Kirim dalam format URL-encoded
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // console.log(data);
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
            } else {
                alert('Masukkan waktu yang valid sebelum azan.');
            }
        }

        // 🕌 Simpan Nama Masjid
        function saveNamaMasjid() {
            const namaMasjid = document.getElementById('nama-masjid').value.trim();
            if (namaMasjid) {
                const formData = new URLSearchParams();
                formData.append("namaMasjid", namaMasjid);

                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString(), // Kirim dalam format URL-encoded
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // console.log(data);
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
            } else {
                alert('Nama masjid tidak boleh kosong.');
            }
        }


        // 📍 Atur Lokasi Masjid
        function setLocationWithGPS() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    const formData = new URLSearchParams();
                    formData.append("latitude", latitude);
                    formData.append("longitude", longitude);
                    formData.append("type_lokasi", 'gps');

                    fetch('api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: formData.toString(), // Kirim dalam format URL-encoded
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        document.getElementById("location-status").innerText = `Lokasi GPS disimpan: Lat ${latitude}, Long ${longitude}`;
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan: ' + error.message);
                    });


                }, (error) => {
                    alert('Gagal mendapatkan lokasi, pastikan GPS aktif.');
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        }

        // ✍️ Simpan Lokasi Manual
        function saveManualLocation() {
            const lokasi = document.getElementById('manual-lokasi').value.trim();
            if (lokasi) {
                const formData = new URLSearchParams();
                    formData.append("city", lokasi);
                    formData.append("type_lokasi", 'manual');

                    fetch('api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: formData.toString(), // Kirim dalam format URL-encoded
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        document.getElementById("location-status").innerText = `Lokasi Manual disimpan: ${lokasi}`;
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan: ' + error.message);
                    });
            } else {
                alert('Silakan masukkan nama lokasi terlebih dahulu.');
            }
        }

        // 💬 Simpan Teks Berjalan
        function saveTeksBerjalan() {
            const teks = document.getElementById('teks-berjalan').value.trim();
            if (teks) {
                const formData = new URLSearchParams();
                formData.append("teks_berjalan", teks);

                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString(), // Kirim dalam format URL-encoded
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // console.log(data);
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
            } else {
                alert('Teks tidak boleh kosong.');
            }
        }

        // 💰 Simpan Informasi Kas
        function saveKasMasjid() {
            let kas_awal = document.getElementById('kas-awal').value;
                kas_awal = parseInt(kas_awal.replace(/[^0-9]/g, ""), 10);

            let kas_masuk = document.getElementById('kas-masuk').value;
                kas_masuk = parseInt(kas_masuk.replace(/[^0-9]/g, ""), 10);

            let kas_keluar = document.getElementById('kas-keluar').value;
                kas_keluar = parseInt(kas_keluar.replace(/[^0-9]/g, ""), 10);

            const formData = new URLSearchParams();
            formData.append("kas_awal", kas_awal);
            formData.append("kas_masuk", kas_masuk);
            formData.append("kas_keluar", kas_keluar);

            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString(), // Kirim dalam format URL-encoded
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // console.log(data);
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }


        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0, // Tidak menggunakan desimal
            }).format(amount);
        }

        function handlePrice(event) {
            const value = event.target.value;
            const numberMatch = value.match(/\d+/g);
            const price = numberMatch ? parseInt(numberMatch.join(''), 10) : 0;
            event.target.value = price == 0 ? '' : formatRupiah(price);
        };
    </script>
</body>
</html>
