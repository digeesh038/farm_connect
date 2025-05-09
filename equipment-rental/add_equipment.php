<?php
session_start();
include('../Includes/db.php');
if (!isset($_SESSION['farmer_id'])) {
    header('Location: auth\FarmerLogin.php');
    exit();
}

if (isset($_POST['add_equipment'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price_per_day = mysqli_real_escape_string($con, $_POST['price_per_day']);
    $image = mysqli_real_escape_string($con, $_FILES['image']['name']);
    $image_tmp = $_FILES['image']['tmp_name'];
    
    move_uploaded_file($image_tmp, "Assets/images/$image");

    $query = "INSERT INTO equipment (name, description, price_per_day, image, status, owner_id) 
              VALUES ('$name', '$description', '$price_per_day', '$image', 'available', '{$_SESSION['farmer_id']}')";
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "<script>alert('Equipment added successfully'); window.location = 'index.php';</script>";
    } else {
        echo "<script>alert('Failed to add equipment');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Equipment</title>
  <link rel="stylesheet" href="Assets/css/style_index.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-image: url('Assets/images/background.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      color: white; /* Set text color to white */
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.9); /* Overlay for better text visibility */
      z-index: -1;
    }

    .container {
      max-width: 500px;
      margin-top: 50px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    /* Simple input styles */
    input, textarea {
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 8px;
      margin-bottom: 15px;
      width: 100%;
      font-size: 14px;
      background-color: #e0e0e0; /* Light grey background for input boxes */
      color: #333; /* Dark text inside input boxes for contrast */
    }

    input[type="file"] {
      padding: 5px;
    }

    /* Button style */
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      padding: 8px 16px;
      font-size: 14px;
      width: 100%;
      border-radius: 4px;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }

    /* Sidebar menu styling */
    #sidebar {
      display: none;
    }
  </style>
</head>
<body>
  
  <div class="container">
    <h2>Add New Equipment</h2>
    <form action="add_equipment.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Equipment Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label for="price_per_day" class="form-label">Price per Day</label>
        <input type="number" name="price_per_day" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" required>
      </div>
      <button type="submit" name="add_equipment" class="btn btn-primary">Add Equipment</button>
    </form>
  </div>

</body>
</html>
