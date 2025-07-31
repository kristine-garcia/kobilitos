<?php
session_start();
if (!isset($_SESSION['user'])) {
  echo "
  <style>
    body {
      background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      text-align: center;
    }
    .login-box h2 {
      color: #ff5e57;
      font-size: 2em;
      margin-bottom: 10px;
    }
    .login-box p {
      font-size: 1.2em;
    }
    .login-box a {
      display: inline-block;
      margin-top: 15px;
      background: #ff5e57;
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .login-box a:hover {
      background: #e04848;
    }
    .emoji {
      font-size: 3em;
    }
  </style>
  <div class='login-box'>
    <div class='emoji'>🍔 🔒 🥤</div>
    <h2>Oops! You're not logged in.</h2>
    <p>Please <a href='login.php'>login</a> to place your delicious order!</p>
  </div>
  ";
  exit;
}

// Handle delete
if (isset($_GET['delete'])) {
  $index = (int)$_GET['delete'];
  $all_orders = file("orders.txt");
  unset($all_orders[$index]);
  file_put_contents("orders.txt", implode("", $all_orders));
  header("Location: order.php");
  exit;
}

// Handle update form
if (isset($_GET['edit'])) {
  $edit_index = (int)$_GET['edit'];
  $all_orders = file("orders.txt");
  $line = trim($all_orders[$edit_index]);
  list($user, $order_data) = explode(": ", $line);
  list($item_name, $item_qty) = explode(" x ", $order_data);
}

// Handle updated data
if (isset($_POST['update'])) {
  $index = (int)$_POST['index'];
  $item = $_POST['item'];
  $qty = $_POST['qty'];
  $all_orders = file("orders.txt");
  $all_orders[$index] = $_SESSION['user'] . ": $item x $qty\n";
  file_put_contents("orders.txt", implode("", $all_orders));
  header("Location: order.php");
  exit;
}

// Handle new order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item']) && !isset($_POST['update'])) {
  $item = $_POST['item'];
  $qty = $_POST['qty'];
  $order = $_SESSION['user'] . ": $item x $qty\n";
  file_put_contents("orders.txt", $order, FILE_APPEND);
  echo "
<style>
  body {
    background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
    font-family: 'Segoe UI', sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .confirmation-box {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-align: center;
    max-width: 500px;
  }
  .confirmation-box h2 {
    color: #28a745;
    font-size: 2em;
    margin-bottom: 10px;
  }
  .confirmation-box p {
    font-size: 1.2em;
    margin-bottom: 20px;
  }
  .confirmation-box a {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
  }
  .confirmation-box a:hover {
    background-color: #218838;
  }
  .emoji {
    font-size: 3em;
    margin-bottom: 10px;
  }
</style>

<div class='confirmation-box'>
  <div class='emoji'>✅🍽️</div>
  <h2>Order Placed Successfully!</h2>
  <p>Your tasty food is on the way.</p>
  <a href='order.php'>← Back to Orders</a>
</div>
";
exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Kobilitos - Order Page</title>
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
      max-width: 700px;
    }

    h2, h3 {
      text-align: center;
      color: #333;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
    }

    select, input[type=number], button {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      background: #ff7e5f;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #eb5757;
    }

    ul {
      padding-left: 20px;
    }

    li {
      margin-bottom: 8px;
    }

    a {
      color: #0077cc;
      text-decoration: none;
      margin-left: 8px;
    }

    .nav-links {
      text-align: center;
      margin-top: 20px;
    }

    .nav-links a {
      margin: 0 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><?php echo isset($edit_index) ? "Edit Order" : "Place Your Order"; ?></h2>
    <form method="POST">
      <label>Menu Item:
        <select name="item">
        <?php
          $menu_items = ["Burger", "Pizza", "Fries", "Soda", "Spaghetti", "Fried Chicken", "Hotdog", "Tacos", "Nachos", "Ice Cream", "Milkshake", "Salad", "Steak", "Ribs", "Grilled Fish", "Sushi", "Tempura", "Chicken Wings", "Mozzarella Sticks", "Garlic Bread", "Coffee", "Fruit Juice"];
          foreach ($menu_items as $item) {
            $selected = isset($item_name) && $item_name == $item ? 'selected' : '';
            echo "<option value='$item' $selected>$item</option>";
          }
        ?>
        </select>
      </label>
      <label>Quantity:
        <input type="number" name="qty" min="1" required value="<?php echo isset($item_qty) ? $item_qty : ''; ?>">
      </label>
      <?php
        if (isset($edit_index)) {
          echo "<input type='hidden' name='index' value='$edit_index'><button type='submit' name='update'>Update Order</button>";
        } else {
          echo "<button type='submit'>Order</button>";
        }
      ?>
    </form>

    <h3>Your Order History</h3>
    <ul>
    <?php
      $all_orders = file("orders.txt");
      foreach ($all_orders as $index => $entry) {
        if (str_starts_with($entry, $_SESSION['user'] . ":")) {
          $order_text = htmlspecialchars(trim(substr($entry, strlen($_SESSION['user']) + 2)));
          echo "<li>$order_text <a href='?edit=$index'>Edit</a> | <a href='?delete=$index' onclick=\"return confirm('Delete this order?');\">Delete</a></li>";
        }
      }
    ?>
    </ul>

    <div class="nav-links">
      <a href="index.html">Back to Home</a> |
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
