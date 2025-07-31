<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if ($username && $password) {
    $users = file_exists('users.txt') ? file('users.txt', FILE_IGNORE_NEW_LINES) : [];
    foreach ($users as $user) {
      list($storedUser, $storedHash) = explode(':', $user);
      if ($storedUser === $username && password_verify($password, $storedHash)) {
        $_SESSION['user'] = $username;
        header("Location: index.html");
        exit;
      }
    }
    $error = "Invalid username or password.";
  } else {
    $error = "Please fill in all fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Kobilitos</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(-45deg, #f6d365, #fda085, #fbc2eb, #a6c1ee);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .container {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
      width: 90%;
      max-width: 400px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input[type=text], input[type=password] {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      padding: 10px;
      border: none;
      background-color: #6a82fb;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #5a67d8;
    }

    .error {
      color: red;
      margin-top: 10px;
      text-align: center;
    }

    .nav-links {
      text-align: center;
      margin-top: 20px;
    }

    .nav-links a {
      margin: 0 10px;
      color: #0077cc;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login to Kobilitos</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <div class="nav-links">
      <p>Don't have an account? <a href="register.php">Register</a></p>
      <a href="index.html">Back to Home</a>
    </div>
  </div>
</body>
</html>
