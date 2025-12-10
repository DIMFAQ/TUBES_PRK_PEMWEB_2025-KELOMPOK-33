<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE email = :e LIMIT 1");
    $stmt->execute([":e" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nama"] = $user["nama"];
        $_SESSION["role"] = $user["role"];
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<form method="POST">
  <label>Email</label><br>
  <input type="email" name="email"><br>
  <label>Password</label><br>
  <input type="password" name="password"><br>
  <button type="submit">Login</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>