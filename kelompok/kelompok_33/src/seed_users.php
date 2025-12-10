<?php
require __DIR__ . '/config.php';
$users = [
    [
        'nama' => 'Admin CleanSpot',
        'email' => 'admin@cleanspot.com',
        'password' => 'admin123',
        'role' => 'admin',
        'telepon' => '081234567890',
        'alamat' => 'Kantor CleanSpot Bandar Lampung'
    ],
    [
        'nama' => 'Petugas Kebersihan',
        'email' => 'petugas@cleanspot.com',
        'password' => 'petugas123',
        'role' => 'petugas',
        'telepon' => '082234567890',
        'alamat' => 'Dinas Kebersihan Bandar Lampung'
    ],
    [
        'nama' => 'Warga Demo',
        'email' => 'warga@cleanspot.com',
        'password' => 'warga123',
        'role' => 'warga',
        'telepon' => '083234567890',
        'alamat' => 'Jl. Contoh No. 123, Bandar Lampung'
    ]
];
echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Seed Demo Users - CleanSpot</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 700px; 
            margin: 50px auto; 
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 { color: #2563eb; margin-top: 0; }
        .success { 
            color: #059669; 
            background: #d1fae5;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error { 
            color: #dc2626; 
            background: #fee2e2;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info { 
            background: #fef3c7; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 5px;
            border-left: 4px solid #f59e0b;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            border: 1px solid #e5e7eb; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background: #2563eb; 
            color: white; 
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Ã°Å¸Å’Â± Seed Demo Users - CleanSpot</h2>
";
try {
    $created = 0;
    $skipped = 0;
    foreach ($users as $user) {
        $stmt = $pdo->prepare("SELECT id FROM pengguna WHERE email = :email");
        $stmt->execute(['email' => $user['email']]);
        if ($stmt->fetch()) {
            echo "<p class='info'>Ã¢Å¡Â Ã¯Â¸Â User <strong>{$user['email']}</strong> sudah ada, skip...</p>";
            $skipped++;
            continue;
        }
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $ins = $pdo->prepare("
            INSERT INTO pengguna (nama, email, password_hash, role, telepon, alamat, created_at, updated_at) 
            VALUES (:nama, :email, :hash, :role, :telepon, :alamat, NOW(), NOW())
        ");
        $ins->execute([
            'nama' => $user['nama'],
            'email' => $user['email'],
            'hash' => $hash,
            'role' => $user['role'],
            'telepon' => $user['telepon'],
            'alamat' => $user['alamat']
        ]);
        echo "<p class='success'>Ã¢Å“â€¦ User <strong>{$user['role']}</strong> berhasil dibuat: {$user['email']}</p>";
        $created++;
    }
    echo "<h3>Ã°Å¸â€œÅ  Summary</h3>";
    echo "<p>Ã¢Å“â€¦ Created: <strong>$created</strong> users</p>";
    echo "<p>Ã¢Å¡Â Ã¯Â¸Â Skipped: <strong>$skipped</strong> users (already exist)</p>";
    echo "<h3>Ã°Å¸â€œâ€¹ Login Credentials</h3>";
    echo "<table>
        <tr>
            <th>Role</th>
            <th>Email</th>
            <th>Password</th>
            <th>Nama</th>
        </tr>";
    foreach ($users as $user) {
        echo "<tr>
            <td><strong>{$user['role']}</strong></td>
            <td>{$user['email']}</td>
            <td><code>{$user['password']}</code></td>
            <td>{$user['nama']}</td>
        </tr>";
    }
    echo "</table>";
    echo "<p class='success'>Ã¢Å“â€¦ Seeding selesai! Silakan login dengan salah satu akun di atas.</p>";
    echo "<a href='login_page.html' class='btn'>Ã°Å¸â€Â Login Sekarang</a>";
} catch (Exception $e) {
    echo "<p class='error'>Ã¢ÂÅ’ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "    </div>
</body>
</html>";