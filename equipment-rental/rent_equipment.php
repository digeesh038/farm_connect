<?php include('../Includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rent Equipment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center">Rent Equipment</h2>
  <?php
  $equipment_id = $_GET['id'];
  $query = "SELECT * FROM equipment WHERE id = '$equipment_id'";
  $result = mysqli_query($con, $query);
  $equipment = mysqli_fetch_assoc($result);
  ?>
  <div class="card">
    <img src="Assets/images/<?php echo $equipment['image']; ?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><?php echo $equipment['name']; ?></h5>
      <p class="card-text"><?php echo $equipment['description']; ?></p>
      <p class="text-success fw-bold">₹<?php echo $equipment['price_per_day']; ?>/day</p>
      <form method="POST">
        <button type="submit" name="rent" class="btn btn-primary">Rent Now</button>
      </form>
    </div>
  </div>

  <?php
  if (isset($_POST['rent'])) {
    session_start();
    $user_id = $_SESSION['farmer_id'];
    $rent_date = date('Y-m-d');
    $return_date = date('Y-m-d', strtotime("+7 days"));

    $query = "INSERT INTO rentals (user_id, equipment_id, rent_date, return_date) VALUES ('$user_id', '$equipment_id', '$rent_date', '$return_date')";
    $result = mysqli_query($con, $query);
 

    if ($result) {
      $update_query = "UPDATE equipment SET status = 'rented' WHERE id = '$equipment_id'";
      mysqli_query($con, $update_query);


      echo "<script>alert('Equipment rented successfully'); window.location = 'index.php';</script>";
    } else {
      echo "<script>alert('Failed to rent equipment');</script>";
    }
  }
  ?>
</div>
</body>
</html>
