<?php
header("Content-Type: application/json");
include 'koneksi.php';

if (isset($_POST['get_data_setting'])) {
    $result = $conn->query("SELECT * FROM setting");
    $lokasi = $conn->query("SELECT * FROM lokasi");

    if ($result->num_rows > 0) {
        echo json_encode([
            "status" => "success",
            "data" => $result->fetch_assoc(),
            "lokasi" => $lokasi->fetch_assoc(),
        ]);
    } 

}

if (isset($_POST['namaMasjid'])) {
    $namaMasjid = htmlspecialchars($_POST['namaMasjid'], ENT_QUOTES, 'UTF-8');
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (nama_masjid) VALUES (?)");
        $stmt->bind_param("s", $namaMasjid);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET nama_masjid = ? LIMIT 1");
        $stmt->bind_param("s", $namaMasjid);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Nama masjid '$namaMasjid' berhasil disimpan!"
    ]);
}

if (isset($_FILES['file'])) {
    $uploadDir = "./assets/audio/";
    $type = $_POST['type'];
    // $maxFileSize = 2 * 1024 * 1024;
    $file = $_FILES['file'];

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
    }

    // if ($file['size'] > $maxFileSize) {
    //     echo json_encode([
    //         "status" => "error",
    //         "message" => "Ukuran file terlalu besar! Maksimal 2MB."
    //     ]);
    //     exit;
    // }


    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $type ."-". time().".".$fileExtension;
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $result = $conn->query("SELECT id FROM setting");

        if ($result->num_rows == 0) {
            if ($type == 'tahrim') $stmt = $conn->prepare("INSERT INTO setting (audio_tahrim) VALUES (?)");
            else $stmt = $conn->prepare("INSERT INTO setting (audio_murottal) VALUES (?)");
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
        } else {
            if ($type == 'tahrim') $stmt = $conn->prepare("UPDATE setting SET audio_tahrim = ? LIMIT 1");
            else $stmt = $conn->prepare("UPDATE setting SET audio_murottal = ? LIMIT 1");
            
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
        }

        $stmt->close();

        echo json_encode([
            "status" => "success",
            "message" => "File berhasil disimpan!"
        ]);
    }else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menyimpan file."
        ]);
    }
}

if (isset($_POST['play_audio'])) {
    $play_audio = $_POST['play_audio'] == "enable" ? true : false;
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (play_audio) VALUES (?)");
        $stmt->bind_param("i", $play_audio);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET play_audio = ? LIMIT 1");
        $stmt->bind_param("i", $play_audio);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Audio berhasil diaktifkan"
    ]);
}

if (isset($_POST['waktu_tahrim'])) {
    $waktu_tahrim = $_POST['waktu_tahrim'];
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (waktu_tahrim) VALUES (?)");
        $stmt->bind_param("i", $waktu_tahrim);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET waktu_tahrim = ? LIMIT 1");
        $stmt->bind_param("i", $waktu_tahrim);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Waktu tahrim berhasil disimpan!"
    ]);
}

if (isset($_POST['waktu_murottal'])) {
    $waktu_murottal = $_POST['waktu_murottal'];
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (waktu_murottal) VALUES (?)");
        $stmt->bind_param("i", $waktu_murottal);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET waktu_murottal = ? LIMIT 1");
        $stmt->bind_param("i", $waktu_murottal);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Waktu murottal berhasil disimpan!"
    ]);
}

if (isset($_POST['teks_berjalan'])) {
    $teks_berjalan = htmlspecialchars($_POST['teks_berjalan'], ENT_QUOTES, 'UTF-8');
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (text_berjalan) VALUES (?)");
        $stmt->bind_param("s", $teks_berjalan);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET text_berjalan = ? LIMIT 1");
        $stmt->bind_param("s", $teks_berjalan);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil disimpan!"
    ]);
}

if (isset($_POST['kas_awal'])) {
    $kas_awal = $_POST['kas_awal'];
    $kas_masuk = $_POST['kas_masuk'];
    $kas_keluar = $_POST['kas_keluar'];
    
    $result = $conn->query("SELECT id FROM setting");

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO setting (kas_awal_masjid, kas_masuk_masjid, kas_keluar_masjid) VALUES (?,?,?)");
        $stmt->bind_param("iii", $kas_awal, $kas_masuk, $kas_keluar);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE setting SET kas_awal_masjid=?, kas_masuk_masjid=?, kas_keluar_masjid=? LIMIT 1");
        $stmt->bind_param("iii", $kas_awal, $kas_masuk, $kas_keluar);
        $stmt->execute();
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil disimpan!"
    ]);
}

if (isset($_POST['type_lokasi'])) {
    $type_lokasi = $_POST['type_lokasi'];
    $result = $conn->query("SELECT id FROM lokasi");

    if ($_POST['type_lokasi'] == 'gps') {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO lokasi (latitude, longitude, type) VALUES (?,?,?)");
            $stmt->bind_param("sss", $latitude, $longitude, $type_lokasi);
            $stmt->execute();
        }else {
            $stmt = $conn->prepare("UPDATE lokasi SET latitude=?,longitude=?,type=?,city=null LIMIT 1");
            $stmt->bind_param("sss", $latitude, $longitude, $type_lokasi);
            $stmt->execute();
        }
    }else {
        $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO lokasi (city, type) VALUES (?,?)");
            $stmt->bind_param("ss", $city, $type_lokasi);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE lokasi SET city=?,type=?,latitude=null,longitude=null LIMIT 1");
            $stmt->bind_param("ss", $city, $type_lokasi);
            $stmt->execute();
        }
    }

    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil disimpan!"
    ]);
}
?>
