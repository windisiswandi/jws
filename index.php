<?php
    include 'koneksi.php';
    $setting = $conn->query("SELECT * FROM setting")->fetch_assoc();
    $text_berjalan = @$setting['text_berjalan'];
    $play_audio = @$setting['play_audio'];
    $audio_tahrim = @$setting['audio_tahrim'];
    $waktu_tahrim = @$setting['waktu_tahrim'] > 0 ? $setting['waktu_tahrim'] : 5;
    $audio_murottal = @$setting['audio_murottal'];
    $waktu_murottal = @$setting['waktu_murottal'] > 0 ? $setting['waktu_murottal'] : 6;
    $kas_awal = @$setting['kas_awal_masjid'] ? $setting['kas_awal_masjid'] : 0;
    $kas_masuk = @$setting['kas_masuk_masjid'] ? $setting['kas_masuk_masjid'] : 0;
    $kas_keluar = @$setting['kas_keluar_masjid'] ? $setting['kas_keluar_masjid'] : 0;
    $total_kas = ($kas_awal + $kas_masuk) - $kas_keluar;

    $lokasi =  $conn->query("SELECT * FROM lokasi")->fetch_assoc();
    if ($lokasi) {
        $latitude = $lokasi['latitude'];
        $longitude = $lokasi['longitude'];
        $id_city = $lokasi['id_city'];
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
    <title>Jadwal Sholat</title>
    <link rel="stylesheet" href="./style/style.css" />
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="./assets/uikit/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="./assets/uikit/js/uikit.min.js"></script>
    <script src="./assets/uikit/js/uikit-icons.min.js"></script>
</head>
<body class="font-sans h-screen w-full box-border">
    <div class="h-screen w-full overflow-hidden">
        <div class="uk-position-relative uk-visible-toggle uk-light hidden lg:block" tabindex="-1" uk-slideshow="animation: fade; autoplay: true; autoplay-interval: 5000">

            <div class="uk-slideshow-items">
                <div>
                    <img class="object-cover h-full" src="./assets/slide1.jpeg" alt="" uk-cover>
                </div>
                <div>
                    <img class="object-cover h-full" src="./assets/slide-2.jpg" alt="" uk-cover>
                </div>
                <div>
                    <img class="object-cover h-full" src="./assets/slide-3.jpeg" alt="" uk-cover>
                </div>
            </div>

            <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slideshow-item="previous"></a>
            <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slideshow-item="next"></a>

        </div>
    </div>
    <div class="absolute left-0 top-0 lg:bg-black lg:bg-opacity-50 h-screen w-full z-10">
        <!-- <div class="grid "></div> -->
        <div class="bg-gradient-to-r from-blue-200 to-white flex justify-between items-center px-2">
            
            <div class="py-3 flex items-center space-x-2">
                <img src="./assets/icon-masjid.png" alt="icon masjid">
                <h1 id="nama_masjid" class="text-2xl 2xl:text-3xl 3xl:text-5xl font-semibold m-0"><?= @$setting['nama_masjid'] ? $setting['nama_masjid'] : "Nama masjid"; ?></h1>
            </div>
            <!-- <a href="admin.php" class="bg-blue-500 !no-underline inline-block text-white py-2 px-3 rounded h-full cursor-pointer xl:text-xl 3xl:text-3xl">
                🔧 Pengaturan
            </a> -->
        </div>
        <div class="mx-auto w-full 3xl:container">
            <div class="text-center bg-slate-800 p-5 text-white">
                <p id="connection-status">Status Koneksi</p>
                <p class="text-2xl 3xl:text-5xl"> <span id="ca-masehi">-</span> / <span id="ca-hijriyah">-</span> </p>
            </div>
            <div class="grid grid-cols-2 text-center border-t-2 border-slate-600">
                <div class="bg-slate-800 text-white px-5 py-8 border-r-2 border-slate-600">
                    <h2 class="uppercase text-white font-bold">Waktu</h2>
                    <div class="flex justify-center">
                        <table id="current-time" class="text-center">
                            <tr class="font-bold text-3xl lg:text-5xl 2xl:text-8xl">
                                <td id="hours">00</td>
                                <td class="px-1 2xl:px-5">:</td>
                                <td id="minutes">00</td>
                                <td class="px-1 2xl:px-5">:</td>
                                <td id="seconds">00</td>
                            </tr>
                            <tr class="text-sm xl:text-xl 2xl:text-[28px]">
                                <td class="py-2 2xl:py-5">jam</td>
                                <td></td>
                                <td>menit</td>
                                <td></td>
                                <td>detik</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="bg-slate-800 text-white px-5 py-8">
                    <h2 id="next-prayer" class="uppercase text-white font-bold">-</h2>
                    <div class="flex justify-center">
                        <table id="countdown" class="text-center">
                            <tr class="font-bold text-3xl lg:text-5xl 2xl:text-8xl">
                                <td id="hours">00</td>
                                <td class="px-1 2xl:px-5">:</td>
                                <td id="minutes">00</td>
                                <td class="px-1 2xl:px-5">:</td>
                                <td id="seconds">00</td>
                            </tr>
                            <tr class="text-sm xl:text-xl 2xl:text-[28px]">
                                <td class="py-2 2xl:py-5">jam</td>
                                <td></td>
                                <td>menit</td>
                                <td></td>
                                <td>detik</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="bg-gray-800 p-4 mt-5 rounded-md text-center">
            <p class="text-yellow-400 font-semibold">
                Sesungguhnya orang yang bodoh di antara kami selalu mengucapkan (perkataan) yang melampaui batas terhadap Allah. [QS. 72:4]
            </p>
        </div> -->
        <audio id="myAudioTahrim" class="invisible absolute">
           <source src="assets/audio/<?= $audio_tahrim ?>" type="audio/mpeg">
           Your browser does not support the audio element.
       </audio>

        <audio id="myAudioMurottal" class="invisible absolute">
           <source src="assets/audio/<?= $audio_murottal ?>" type="audio/mpeg">
           Your browser does not support the audio element.
       </audio>

        <div class="lg:absolute left-0 bottom-0 w-full">

            <div class="grid grid-cols tablet:grid-cols-3 lg:grid-cols-7 text-center">
                <div class='bg-pink-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>IMSAK</p>
                    <p id="imsak" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-red-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>SUBUH</p>
                    <p id="subuh" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-yellow-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>SYURUQ</p>
                    <p id="syuruq" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-green-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>DZUHUR</p>
                    <p id="dzuhur" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-blue-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>ASHAR</p>
                    <p id="ashar" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-orange-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>MAGHRIB</p>
                    <p id="maghrib" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
                <div class='bg-purple-500 text-center py-5 text-white uppercase xl:bg-opacity-85'>
                    <p class='font-bold text-xl xl:text-3xl 3xl:text-[58px]'>ISYA'</p>
                    <p id="isya" class='font-semibold text-5xl xl:text-6xl 3xl:text-8xl mt-1'>-</p>
                </div>
            </div>
            <marquee class="bg-gradient-to-r from-white to-blue-200 text-black font-semibold py-3 text-xl 2xl:text-5xl" id="teks-berjalan-display">
                <?php if(@$setting['text_berjalan']) : ?>
                    <?= "🎤 $text_berjalan | 💰 Kas Awal: Rp ". number_format($kas_awal, 0, ",", '.') . " | 💰 Kas Masuk: Rp " . number_format($kas_masuk, 0, ",", '.') . " | 💸 Kas Keluar: Rp " . number_format($kas_keluar, 0, ",", '.') . " | 💼 Total Kas: Rp" . number_format($total_kas, 0, ",", '.'); ?>
                <?php else: ?>
                    🎤 Menunggu update teks dari Admin Panel...
                <?php endif; ?>
            </marquee>
        </div>
    </div>

    <?php if(!(@$latitude || @$longitude || @$city)) : ?>
        <script>alert('Lokasi belum disetting')</script>
    <?php endif; ?>

    <script>
        document.addEventListener('click', function () {
            document.documentElement.requestFullscreen();
        })

        var audioTahrim = document.getElementById("myAudioTahrim");
        var audioMurottal = document.getElementById("myAudioMurottal");

        let countdownInterval = null;
        let play_audio = parseInt('<?= $play_audio; ?>');
        let countPlayTahrim = 0;
        let countPlayMurottal = 0;
        let srcAudioTahrim = `<?= $audio_tahrim ?>`;
        let srcAudioMurottal = `<?= $audio_murottal ?>`;
        let isOnline = false;
        let hasReqOffline = false;
        let hasReqOnline = false;
        let waktu_tahrim = parseInt('<?= @$waktu_tahrim ?>');
        let waktu_murottal = parseInt('<?= @$waktu_murottal ?>');
        let latitude = '<?= @$latitude ?>';
        let longitude = '<?= @$longitude ?>';
        let id_city = '<?= @$id_city ?>';
        let city = '<?= @$city ?>';
        let type_lokasi = '<?= @$type_lokasi ?>';

        function updatePrayerTimes(timings) {
            document.getElementById("imsak").innerText = timings.imsak;
            document.getElementById("subuh").innerText = timings.subuh;
            document.getElementById("syuruq").innerText = timings.dhuha;
            document.getElementById("dzuhur").innerText = timings.dzuhur;
            document.getElementById("ashar").innerText = timings.ashar;
            document.getElementById("maghrib").innerText = timings.maghrib;
            document.getElementById("isya").innerText = timings.isya;
        }

        function countdownToNextPrayer(timings) {
            if (countdownInterval) clearInterval(countdownInterval);

            const prayerTimes = {
                Imsak: timings.imsak,
                Subuh: timings.subuh,
                Syuruq: timings.dhuha,
                Dzuhur: timings.dzuhur,
                Ashar: timings.ashar,
                Maghrib: timings.maghrib,
                Isya: timings.isya
            };

            const now = new Date();
            let nextPrayer = null;
            let nextPrayerTime = null;

            Object.entries(prayerTimes).forEach(([name, time]) => {
                const [hours, minutes] = time.split(":");
                const prayerDate = new Date();
                prayerDate.setHours(hours, minutes, 0);

                if (prayerDate > now && !nextPrayer) {
                    nextPrayer = name.toUpperCase();
                    nextPrayerTime = prayerDate;
                }
            });

            if (nextPrayer) {
                document.getElementById("next-prayer").innerText = nextPrayer;

                countdownInterval = setInterval(() => {
                    const now = new Date();
                    const diff = nextPrayerTime - now;

                    if(diff > 0) {
                        const hours = Math.floor(diff / 3600000);
                        const minutes = Math.floor((diff % 3600000) / 60000);
                        const seconds = Math.floor((diff % 60000) / 1000);
    
                        document.querySelector("#countdown #hours").innerText = hours < 10 ? `0${hours}` : hours;
                        document.querySelector("#countdown #minutes").innerText = minutes < 10 ? `0${minutes}` : minutes;
                        document.querySelector("#countdown #seconds").innerText = seconds < 10 ? `0${seconds}` : seconds;
                    }else {
                        clearInterval(countdownInterval);
                    }

                    if (play_audio) {
                        if (Math.floor(diff/60000) == waktu_tahrim && countPlayTahrim == 0) {
                            countPlayTahrim = 1;
                            audioTahrim.pause();
                            audioTahrim.src = `./assets/audio/${srcAudioTahrim}`;
                            audioTahrim.load();
                            audioTahrim.play();
                            audioMurottal.pause();
                        }
    
                        if (Math.floor(diff/60000) == waktu_murottal && countPlayMurottal == 0) {
                            countPlayMurottal = 1;
                            audioTahrim.pause();
                            audioMurottal.pause();
                            audioMurottal.src = `./assets/audio/${srcAudioMurottal}`;
                            audioMurottal.load();
                            audioMurottal.play();
                        }
                    }

                }, 1000);
            }
        }
        
        function fetchPrayerTimes() {

            // if (countdownInterval) {
            //     clearInterval(countdownInterval); // Bersihkan interval sebelum mengambil waktu sholat baru
            // }

            let apiUrl = '';
            if (type_lokasi == 'gps') apiUrl = `https://api.aladhan.com/v1/timings?latitude=${latitude}&longitude=${longitude}&method=2`;
            else apiUrl = `https://api.myquran.com/v2/sholat/jadwal/${id_city}/${new Date().getFullYear()}/${new Date().getMonth()+1}/${new Date().getDate()}`;

            if (!isOnline) {
                let prayerTime = localStorage.getItem("prayerTime");
                if (prayerTime) {
                    let time = JSON.parse(prayerTime);
                    updatePrayerTimes(time);
                    countdownToNextPrayer(time);
                    showConnectionStatus(false);
                }
            }

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const timings = data.data.jadwal;

                    localStorage.setItem("prayerTime", JSON.stringify(timings));
                    updatePrayerTimes(timings);
                    showConnectionStatus(true);
                    countdownToNextPrayer(timings);
                })
                .catch(error => {
                    console.error("Error fetching prayer times:", error);
                    // hasReqOnline = false;
                    showConnectionStatus(false);
                });
            
        }

        function showConnectionStatus(online) {
            const statusElement = document.getElementById("connection-status");
            if (online) {
                isOnline = true;
                statusElement.innerText = "🟢 Koneksi Internet Aktif";
                statusElement.className = "text-green-800 text-lg font-semibold inline-block bg-green-100 py-1 px-3 rounded-md mb-3";
            } else {
                isOnline = false;
                statusElement.innerText = "🔴 Koneksi Terputus (Mode Offline)";
                statusElement.className = "text-red-800 text-lg font-semibold inline-block bg-red-100 py-1 px-3 rounded-md mb-3";
            }
        }

        function currenTime() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString("id-ID", { 
                weekday: "long", 
                day: "numeric", 
                month: "short", 
                year: "numeric"
            });
            
            const h = now.getHours() < 10 ? `0${now.getHours()}` : now.getHours();
            const m = now.getMinutes() < 10 ? `0${now.getMinutes()}` : now.getMinutes();
            const s = now.getSeconds() < 10 ? `0${now.getSeconds()}` : now.getSeconds();
    
            document.getElementById("ca-masehi").innerText = `${formattedDate}`;
            document.querySelector("#current-time #hours").innerText = h;
            document.querySelector("#current-time #minutes").innerText = m;
            document.querySelector("#current-time #seconds").innerText = s;
        }

        function currenTimeHijri() {
            fetch('https://api.myquran.com/v2/cal/hijr/?adj=-1')
                .then(response => response.json())
                .then(data => {
                    const [day, hijrdate, masehidate] = data.data.date;
                    document.getElementById("ca-hijriyah").innerText = hijrdate;
                })
                .catch(error => {
                    console.error("Error fetching hijri date:", error);
                });
        }

        setInterval(currenTime, 1000);
        // setInterval(fetchPrayerTimes, 1000);

        setInterval(() => {
            const formData = new URLSearchParams();
            formData.append("get_data_setting", true);
            fetch('api.php', {
                method: 'POST',
                body: formData, // Jangan set 'Content-Type', browser akan menentukannya sendiri
            })
            .then(response => response.json())
            .then(result => {
                const { data, lokasi } = result
                const kas_awal = parseInt(data.kas_awal_masjid);
                const kas_masuk = parseInt(data.kas_masuk_masjid);
                const kas_keluar = parseInt(data.kas_keluar_masjid);
                let teks_berjalan = `🎤 ${data.text_berjalan} | 💰 Kas Awal: ${formatRupiah(kas_awal)} | 💰 Kas Masuk: ${formatRupiah(kas_masuk)} | 💸 Kas Keluar: ${formatRupiah(kas_keluar)} | 💼 Total Kas: ${formatRupiah(kas_awal + kas_masuk - kas_keluar)}`;

                
                play_audio = parseInt(data.play_audio);
                waktu_murottal = parseInt(data.waktu_murottal);
                latitude = lokasi.latitude;
                longitude = lokasi.longitude;
                id_city = lokasi.id_city;
                city = lokasi.city;
                type_lokasi = lokasi.type;

                if (!play_audio) {
                    audioTahrim.pause();
                    audioMurottal.pause();
                    countPlayTahrim = 0;
                    countPlayMurottal = 0;
                }

                if (srcAudioTahrim != data.audio_tahrim) {
                    srcAudioTahrim = data.audio_tahrim;
                    countPlayTahrim = 0;
                }

                if (srcAudioMurottal != data.audio_murottal) {
                    waktu_murottal = parseInt(data.waktu_murottal);
                    srcAudioMurottal = data.audio_murottal;
                    countPlayMurottal = 0;
                }

                if (waktu_tahrim != parseInt(data.waktu_tahrim)) {
                    waktu_tahrim = parseInt(data.waktu_tahrim);
                    countPlayTahrim = 0;
                }

                if (waktu_murottal != parseInt(data.waktu_murottal)) {
                    waktu_murottal = parseInt(data.waktu_murottal);
                    countPlayMurottal= 0;
                }

                fetchPrayerTimes();

                document.getElementById("nama_masjid").innerText = `${data.nama_masjid}`;
                document.getElementById("teks-berjalan-display").innerText = `${teks_berjalan}`;
            })
            .catch(error => {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }, 10000);

        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0, // Tidak menggunakan desimal
            }).format(amount);
        }

        currenTime();
        currenTimeHijri();
        fetchPrayerTimes();
    </script>
</body>
</html>
