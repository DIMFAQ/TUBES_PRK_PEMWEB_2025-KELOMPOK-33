<?php
require '';

$judul      = $_POST['judul'];
$kategori   = $_POST['kategori'];
$deskripsi  = $_POST['deskripsi'];
$alamat     = $_POST['alamat'];
$lat        = $_POST['lat'];
$lng        = $_POST['lng'];

$pengguna_id = 1;  

$folder = "uploads/";
$nama_file = time() . "_" . basename($_FILES['foto']['name']);
$target_file = $folder . $nama_file;

if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
    die("Upload foto gagal!");
}

$sql = "INSERT INTO laporan (pengguna_id, judul, deskripsi, kategori, alamat, lat, lng)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "issssdd", $pengguna_id, $judul, $deskripsi, $kategori, $alamat, $lat, $lng);

if (!mysqli_stmt_execute($stmt)) {
    die("Gagal simpan laporan: " . mysqli_error($conn));
}

$laporan_id = mysqli_insert_id($conn);


$sqlFoto = "INSERT INTO foto_laporan (laporan_id, nama_file, path_file, ukuran, tipe_file)
            VALUES (?, ?, ?, ?, ?)";

$stmt2 = mysqli_prepare($conn, $sqlFoto);
$ukuran = $_FILES['foto']['size'];
$tipe   = $_FILES['foto']['type'];

mysqli_stmt_bind_param($stmt2, "issis", $laporan_id, $nama_file, $target_file, $ukuran, $tipe);
mysqli_stmt_execute($stmt2);

header("Location: laporan.php");
exit;

?>
