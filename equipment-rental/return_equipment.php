<?php
session_start();
include('../Includes/db.php');
if (!isset($_SESSION['farmer_id'])) {
    header('Location: auth/FarmerLogin.php');
    exit();
}

$rent_id = $_GET['id'] ?? null;
$equipment_id = $_GET['e_id'] ?? null;

if (!$rent_id && !$equipment_id) {
    echo "Invalid request.";
    exit();
}

// Fetch rental info
$query = "SELECT rentals.*, equipment.name, equipment.price_per_day 
          FROM rentals 
          JOIN equipment ON rentals.equipment_id = equipment.id 
          WHERE rentals.id = '$rent_id' AND rentals.user_id = '{$_SESSION['farmer_id']}'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Rental not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Calculate number of days rented
$rent_date = new DateTime($row['rent_date']);
$return_date = new DateTime(); // current time
$days_rented = $rent_date->diff($return_date)->days;
$days_rented = max(1, $days_rented); // minimum 1 day
$total_amount = $days_rented * $row['price_per_day'];


// On POST: simulate payment and return equipment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update rental status
    $update = mysqli_query($con, "UPDATE rentals SET status='completed', return_date=NOW(), payment_amount='$total_amount' WHERE id='$rent_id'");
    
    
    if ($update) {
        $update_query = "UPDATE equipment SET status = 'available' WHERE id = '$equipment_id'";
    mysqli_query($con, $update_query);
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating return.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center mb-4">Complete Payment</h2>
  <div class="card mx-auto" style="max-width: 500px;">
    <div class="card-body">
      <h5 class="card-title"><?php echo $row['name']; ?></h5>
      <p>Rented for: <strong><?php echo $days_rented; ?></strong> day(s)</p>
      <p>Price per day: ₹<?php echo $row['price_per_day']; ?></p>
      <p>Total to Pay: <strong class="text-success">₹<?php echo $total_amount; ?></strong></p>

      <form method="POST">
        <button type="submit" class="btn btn-success w-100">Pay & Return</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
