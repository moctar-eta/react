<?php
// dashboard.php : page protégée, accessible seulement après connexion
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../frontend/cpp.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../frontend/style.css">
</head>
<body>
  <main class="card">
    <h1>Dashboard</h1>
    <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong> !</p>
    <form action="logout.php" method="post">
      <button type="submit">Se déconnecter</button>
    </form>
  </main>
</body>
</html>
