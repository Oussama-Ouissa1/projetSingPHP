<?php 
include "conn.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérifie si l'utilisateur existe
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        session_start();
        
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullName'] = $user['fullName'];
        header("Location: welcome.php"); // redirige vers une page protégée
        exit;
    } else {
        $message = "<p class='text-red-500 text-center'>Email ou mot de passe incorrect.</p>";
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">

  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
        Sign in to your account
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <!-- Message d'erreur -->
      <?php if (!empty($message)) echo $message; ?>

      <form class="space-y-6" action="" method="POST">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email address</label>
          <div class="mt-2">
            <input type="email" name="email" id="email" autocomplete="email" required
              class="block w-full rounded-md bg-white dark:bg-gray-700 px-3 py-1.5 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
          <div class="mt-2">
            <input type="password" name="password" id="password" autocomplete="current-password" required
              class="block w-full rounded-md bg-white dark:bg-gray-700 px-3 py-1.5 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-indigo-600">
            Sign in
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
        Not a member?
        <a href="singUp.php" class="font-semibold text-indigo-600 hover:text-indigo-500">Sign Up</a>
      </p>
    </div>
  </div>

</body>
</html>
