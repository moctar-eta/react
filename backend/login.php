<?php
// login.php : reçoit le formulaire (méthode POST) et vérifie les identifiants
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Méthode non autorisée.");
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    exit("Veuillez remplir tous les champs.");
}

// Exemple : un seul utilisateur de test.
// Le hash est celui de "123456" (créé avec password_hash).
$utilisateurs = [
    "admin" => password_hash("123456", PASSWORD_DEFAULT),
];

if (isset($utilisateurs[$username]) && password_verify($password, $utilisateurs[$username])) {
    $_SESSION["user"] = $username;
    echo "Bienvenue, " . htmlspecialchars($username) . " !";
} else {
    http_response_code(401);
    echo "Nom d'utilisateur ou mot de passe incorrect.";
}
