<?php
session_start();
// Simple admin access based on hardcoded username
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
  echo "
  <style>
    body {
      background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .denied-box {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      text-align: center;
      max-width: 500px;
    }

    .denied-box h2 {
      font-size: 2em;
      color: #e63946;
      margin-bottom: 10px;
    }

    .denied-box p {
      font-size: 1.2em;
      margin-bottom: 20px;
    }

    .denied-box a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #ff5e57;
      color: white;
      border-radius: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .denied-box a:hover {
      background-color: #d93744;
    }

    .emoji {
      font-size: 3em;
    }
  </style>

  <div class='denied-box'>
    <div class='emoji'>🚫👮‍♂️🔐</div>
    <h2>Access Denied!</h2>
    <p>Only the Admin Chef is allowed in the secret kitchen.</p>
    <a href='login.php'>Go to Login</a>
  </div>
  ";
  exit;
}

?>
<h2>All Orders (Admin View)</h2>
<ul>
<?php
$all_orders = file("orders.txt");
foreach ($all_orders as $entry) {
  echo "<li>" . htmlspecialchars(trim($entry)) . "</li>";
}
?>
</ul>
<p><a href="order.php">Back to User Orders</a></p>
<p><a href="logout.php">Logout</a></p>
