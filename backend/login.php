<?php
// login.php : vérifie l'utilisateur dans la table "users", puis redirige vers le dashboard
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../frontend/cpp.html");
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=login;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    session_regenerate_id(true);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    header("Location: dashboard.php");
    exit;
}

// Échec : retour à la page de connexion avec un message
header("Location: ../frontend/cpp.html?error=1");
exit;
