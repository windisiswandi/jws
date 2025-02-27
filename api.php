<?php
header("Content-Type: application/json");
include 'koneksi.php';

if (isset($_POST['namaMasjid'])) {
    $namaMasjid = $_POST['namaMasjid'];
    
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
    $teks_berjalan = $_POST['teks_berjalan'];
    
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
        $city = $_POST['city'];
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
