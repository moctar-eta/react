const form = document.getElementById("loginForm");
const message = document.getElementById("message");

form.addEventListener("submit", (event) => {
  event.preventDefault();

  const username = form.username.value.trim();
  const password = form.password.value;

  if (password.length < 6) {
    message.className = "";
    message.textContent = "Le mot de passe doit contenir au moins 6 caractères.";
    return;
  }

  message.className = "ok";
  message.textContent = "Bienvenue, " + username + " !";
});