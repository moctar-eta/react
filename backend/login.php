<?php
// register.php : crée la table "users" si elle n'existe pas, puis enregistre un utilisateur

// --- Connexion à MySQL (WAMP : utilisateur root, mot de passe vide) ---
$host = "localhost";
$dbname = "login";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// --- Création de la table (une seule fois, ignorée si elle existe déjà) ---
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// --- Enregistrement ---
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || strlen($password) < 6) {
        $message = "Nom requis et mot de passe de 6 caractères minimum.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            $message = "Utilisateur enregistré avec succès.";
        } catch (PDOException $e) {
            // 1062 = doublon (nom déjà utilisé)
            $message = ($e->errorInfo[1] === 1062)
                ? "Ce nom d'utilisateur existe déjà."
                : "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="card">
    <h1>Inscription</h1>
    <form method="post" action="register.php">
      <label for="username">Nom d'utilisateur</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required minlength="6">

      <button type="submit">Créer le compte</button>
      <p id="message" role="status"><?= htmlspecialchars($message) ?></p>
    </form>
  </main>
</body>
</html>
