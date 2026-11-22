<?php
// register.php : crée la table "users" si besoin et enregistre un utilisateur

try {
    $pdo = new PDO("mysql:host=localhost;dbname=login;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Utilise le formulaire : frontend/register.html");
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || strlen($password) < 6) {
    exit("Nom requis et mot de passe de 6 caractères minimum.");
}

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
    echo "Compte créé avec succès. <a href='../frontend/cpp.html'>Se connecter</a>";
} catch (PDOException $e) {
    if ($e->errorInfo[1] === 1062) {
        echo "Ce nom d'utilisateur existe déjà. <a href='../frontend/register.html'>Retour</a>";
    } else {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
