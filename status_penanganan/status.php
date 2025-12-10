<?php
session_start();

// =================== DUMMY DATA ===========================
$laporan = [
    ["id"=>1, "lokasi"=>"Jl. Soekarno Hatta", "pelapor"=>"Alyaa", "tanggal"=>"07-12-2025", "status"=>"Baru"],
    ["id"=>2, "lokasi"=>"Jl. Teuku Umar", "pelapor"=>"Nabila", "tanggal"=>"08-12-2025", "status"=>"Diproses"],
    ["id"=>3, "lokasi"=>"Pasar Gintung", "pelapor"=>"Dimas", "tanggal"=>"09-12-2025", "status"=>"Selesai"],
];

// =================== INISIALISASI HISTORY =================
if (!isset($_SESSION["history"])) {
    $_SESSION["history"] = [];
}

// ================== UPDATE STATUS =========================
if (isset($_POST["update_status"])) {
    $id = $_POST["laporan_id"];
    $newStatus = $_POST["statusSelect"];

    $_SESSION["history"][] = [
        "laporan_id" => $id,
        "status" => $newStatus,
        "waktu" => date("d-m-Y H:i:s"),
        "petugas" => "Admin"
    ];

    echo "<script>alert('Status berhasil diperbarui ke: $newStatus'); window.location='status_penanganan.php';</script>";
}

// ================== UPLOAD ================================
if (isset($_POST["upload_bukti"])) {
    $file = $_FILES["buktiFoto"];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . "-" . basename($file["name"]);
    $targetPath = $targetDir . $fileName;

    if(move_uploaded_file($file["tmp_name"], $targetPath)){
        echo "<script>alert('Upload berhasil!'); window.location='status_penanganan.php';</script>";
    } else {
        echo "<script>alert('Upload gagal!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Status Penanganan - CleanSpot</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#eef2f5; }
.sidebar { height:100vh; background:#fff; border-right:1px solid #ddd; padding:20px; position:fixed; width:240px; }
.sidebar a { display:block; margin:10px 0; color:#333; font-weight:600; text-decoration:none; }
.sidebar a:hover { color:#198754; }
.content { margin-left:260px; padding:25px; }
.table-row:hover { background:#f0f7ff; cursor:pointer; }
.status-badge { padding:6px 12px; border-radius:8px; font-size:0.85rem; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="fw-bold text-success mb-4">CleanSpot</h4>
    <a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="#"><i class="bi bi-people"></i> Kelola Pengguna</a>
    <a href="#" class="text-success"><i class="bi bi-list-check"></i> Status Penanganan</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- CONTENT -->
<div class="content">
    <h3 class="fw-bold mb-3">📍 Status Penanganan Sampah</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body table-responsive">
            <table class="table align-middle text-center">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="reportTable">
                <?php foreach($laporan as $index => $item): ?>
                <tr class="table-row">
                    <td><?= $index+1 ?></td>
                    <td><?= $item["lokasi"] ?></td>
                    <td><?= $item["pelapor"] ?></td>
                    <td><?= $item["tanggal"] ?></td>

                    <td class="status-column">
                        <?php
                        $color = [
                            "Baru" => "bg-danger text-white",
                            "Diproses" => "bg-warning text-dark",
                            "Selesai" => "bg-success text-white"
                        ];
                        ?>
                        <span class="status-badge <?= $color[$item['status']] ?>"><?= $item["status"] ?></span>
                    </td>

                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="openStatusModal(<?= $item['id'] ?>)">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <?php if ($item["status"] === "Selesai"): ?>
                        <button class="btn btn-sm btn-primary" onclick="openUploadModal(<?= $item['id'] ?>)">
                            <i class="bi bi-upload"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- HISTORY TABLE -->
    <h4 class="fw-bold text-secondary">📜 Riwayat Perubahan Status</h4>
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ID Laporan</th>
                <th>Status Baru</th>
                <th>Waktu</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($_SESSION["history"] as $h): ?>
            <tr>
                <td><?= $h["laporan_id"] ?></td>
                <td><?= $h["status"] ?></td>
                <td><?= $h["waktu"] ?></td>
                <td><?= $h["petugas"] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- MODALS -->
<div class="modal fade" id="statusModal">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Penanganan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="laporanId" name="laporan_id">
                <label>Status baru</label>
                <select class="form-select" name="statusSelect">
                    <option value="Baru">Baru</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success" name="update_status" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="uploadId" name="laporan_id">
                <input type="file" class="form-control" name="buktiFoto" accept="image/*">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" name="upload_bukti" type="submit">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(id){
    document.getElementById("laporanId").value = id;
    new bootstrap.Modal(document.getElementById("statusModal")).show();
}

function openUploadModal(id){
    document.getElementById("uploadId").value = id;
    new bootstrap.Modal(document.getElementById("uploadModal")).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>