<?php
    include 'koneksi.php';
    $setting = $conn->query("SELECT * FROM setting")->fetch_assoc();
    $text_berjalan = @$setting['text_berjalan'];
    $audio_tahrim = @$setting['audio_tahrim'];
    $waktu_tahrim = @$setting['waktu_tahrim'] > 0 ? $setting['waktu_tahrim'] : 5;
    $audio_murottal = @$setting['audio_murottal'];
    $waktu_murottal = @$setting['waktu_murottal'] > 0 ? $setting['waktu_murottal'] : 6;
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
    <title>Jadwal Sholat</title>
    <link rel="stylesheet" href="./style/style.css" />
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.23.1/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.23.1/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.23.1/dist/js/uikit-icons.min.js"></script>
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
                <h1 class="text-2xl 2xl:text-3xl 3xl:text-5xl font-semibold m-0"><?= @$setting['nama_masjid'] ? $setting['nama_masjid'] : "Nama masjid"; ?></h1>
            </div>
            <a href="admin.php" class="bg-blue-500 !no-underline inline-block text-white py-2 px-3 rounded h-full cursor-pointer xl:text-xl 3xl:text-3xl">
                🔧 Pengaturan
            </a>
        </div>
        <div class="mx-auto w-full 3xl:container">
            <div class="text-center bg-slate-800 p-5 text-white">
                <p id="connection-status">Status Koneksi</p>
                <p class="text-2xl 3xl:text-5xl"> <span id="ca-masehi">Jumat, 11-10-2024</span> / <span id="ca-hijriyah">8 Rabiul Akhir 1446 H</span> </p>
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
                    <?= "🎤 $text_berjalan | 💰 Kas Awal: Rp". number_format($kas_awal, 0, ",", '.') . " | 💰 Kas Masuk: Rp" . number_format($kas_masuk, 0, ",", '.') . " | 💸 Kas Keluar: Rp" . number_format($kas_keluar, 0, ",", '.') . " | 💼 Total Kas: Rp" . number_format($total_kas, 0, ",", '.'); ?>
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
        var audioTahrim = document.getElementById("myAudioTahrim");
        var audioMurottal = document.getElementById("myAudioMurottal");

        function updatePrayerTimes(timings) {
            document.getElementById("imsak").innerText = timings.Imsak;
            document.getElementById("subuh").innerText = timings.Fajr;
            document.getElementById("syuruq").innerText = timings.Sunrise;
            document.getElementById("dzuhur").innerText = timings.Dhuhr;
            document.getElementById("ashar").innerText = timings.Asr;
            document.getElementById("maghrib").innerText = timings.Maghrib;
            document.getElementById("isya").innerText = timings.Isha;
        }

        function countdownToNextPrayer(timings) {
            const prayerTimes = {
                Imsak: timings.Imsak,
                Subuh: timings.Fajr,
                Syuruq: timings.Sunrise,
                Dzuhur: timings.Dhuhr,
                Ashar: timings.Asr,
                Maghrib: timings.Maghrib,
                Isya: timings.Isha
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

                const interval = setInterval(() => {
                    const now = new Date();
                    const diff = nextPrayerTime - now;

                    if(diff <= 0) {
                        window.location.reload();
                    }else {
                        const hours = Math.floor(diff / 3600000);
                        const minutes = Math.floor((diff % 3600000) / 60000);
                        const seconds = Math.floor((diff % 60000) / 1000);
    
                        document.querySelector("#countdown #hours").innerText = hours < 10 ? `0${hours}` : hours;
                        document.querySelector("#countdown #minutes").innerText = minutes < 10 ? `0${minutes}` : minutes;
                        document.querySelector("#countdown #seconds").innerText = seconds < 10 ? `0${seconds}` : seconds;
                    }

                    if (Math.floor(diff/60000) == parseInt("<?= $waktu_tahrim ?>")) {
                        audioTahrim.play();
                        audioMurottal.pause();
                    }

                    if (Math.floor(diff/60000) == parseInt("<?= $waktu_murottal ?>")) {
                        audioTahrim.pause();
                        audioMurottal.play();
                    }

                }, 1000);
            }else {
                document.getElementById("next-prayer").innerText = "Imsak";
                const interval = setInterval(() => {
                    const now = new Date();
                    const nextPrayerTime = new Date();
                    nextPrayerTime.setDate(nextPrayerTime.getDate() + 1);
                    const [hours, minutes] = prayerTimes.Imsak.split(":");
                    nextPrayerTime.setHours(hours, minutes, 0);

                    const diff = nextPrayerTime - now;

                    if(diff <= 0) {
                        window.location.reload();
                    }else {
                        const hours = Math.floor(diff / 3600000);
                        const minutes = Math.floor((diff % 3600000) / 60000);
                        const seconds = Math.floor((diff % 60000) / 1000);
    
                        document.querySelector("#countdown #hours").innerText = hours < 10 ? `0${hours}` : hours;
                        document.querySelector("#countdown #minutes").innerText = minutes < 10 ? `0${minutes}` : minutes;
                        document.querySelector("#countdown #seconds").innerText = seconds < 10 ? `0${seconds}` : seconds;
                    }

                }, 1000);
            }
        }
        
        function fetchPrayerTimes() {

            let apiUrl = '';

            <?php if($type_lokasi == 'gps') : ?>
                apiUrl = `https://api.aladhan.com/v1/timings?latitude=<?= $latitude ?>&longitude=<?= $longitude ?>&method=2`;
            <?php else: ?>
                apiUrl = `https://api.aladhan.com/v1/timingsByCity?city=<?= $city ?>&country=indonesia&method=2`;
            <?php endif; ?>

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const timings = data.data.timings;
                    const masehidata = new Date(data.data.date.timestamp*1000);
                    const formattedDate = masehidata.toLocaleDateString("id-ID", { 
                        weekday: "long", 
                        day: "numeric", 
                        month: "short", 
                        year: "numeric"
                    });

                    const hijridata = data.data.date.hijri;
                    updatePrayerTimes(timings);
                    showConnectionStatus(true);
                    document.getElementById("ca-masehi").innerText = `${formattedDate}`;
                    document.getElementById("ca-hijriyah").innerText = `${hijridata.day} ${hijridata.month.en} ${hijridata.year} H`;
                    countdownToNextPrayer(timings);
                })
                .catch(error => {
                    console.error("Error fetching prayer times:", error);
                    showConnectionStatus(false);
                });
            
        }



        function showConnectionStatus(isOnline) {
            const statusElement = document.getElementById("connection-status");
            if (isOnline) {
                statusElement.innerText = "🟢 Koneksi Internet Aktif";
                statusElement.className = "text-green-500 font-semibold";
            } else {
                statusElement.innerText = "🔴 Koneksi Terputus (Mode Offline)";
                statusElement.className = "text-red-500 font-semibold";
            }
        }

        function currenTime() {

            const now = new Date();
            const h = now.getHours() < 10 ? `0${now.getHours()}` : now.getHours();
            const m = now.getMinutes() < 10 ? `0${now.getMinutes()}` : now.getMinutes();
            const s = now.getSeconds() < 10 ? `0${now.getSeconds()}` : now.getSeconds();
    
            document.querySelector("#current-time #hours").innerText = h;
            document.querySelector("#current-time #minutes").innerText = m;
            document.querySelector("#current-time #seconds").innerText = s;
        }
        setInterval(currenTime, 1000);


        currenTime();
        fetchPrayerTimes();
    </script>
</body>
</html>
