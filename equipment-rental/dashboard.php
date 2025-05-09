<?php
session_start();
include('../Includes/db.php');
if (!isset($_SESSION['farmer_id'])) {
    header('Location: auth\FarmerLogin.php');
    exit();
}

$user_id = $_SESSION['farmer_id'];
$query = "SELECT * , rentals.id as rent_id,equipment.id as equipment_id FROM rentals JOIN equipment ON rentals.equipment_id = equipment.id WHERE rentals.user_id = '$user_id' AND rentals.status = 'active'";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Dashboard</title>
  <link rel="stylesheet" href="Assets/css/style_index.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-image: url('Assets/images/background.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: -1;
    }

    .card {
      animation: fadeInUp 0.8s ease-in-out;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .btn-danger {
      transition: transform 0.2s ease-in-out, background-color 0.3s ease-in-out;
    }

    .btn-danger:hover {
      transform: scale(1.05);
      background-color: #c82333; /* Slightly darker red */
    }

    /* Ensures all images inside the cards have the same size */
    .card-img-top {
      width: 100%; /* Ensures the image spans the full width of the card */
      height: 300px; /* Set a fixed height for all images */
      object-fit: cover; /* Ensures the image covers the space while maintaining its aspect ratio */
      border-radius: 4px; /* Optional: Adds rounded corners to the images */
    }
  </style>
</head>
<body>
<div class="container mt-5">
<nav id="sidebar" class="menu-open">
    <ul>
       <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="index.php">Equipments</a></li>
        <li><a href="add_equipment.php">Renting Out</a></li>
    </ul>
</nav>
<div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

  <h2 class="text-center mb-4 text-white">My Dashboard</h2>
  <div class="row">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="col-md-4">
        <div class="card mb-4 shadow">
          <img src="Assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['name']; ?></h5>
            <p class="card-text"><?php echo $row['description']; ?></p>
            <p class="text-success fw-bold">₹<?php echo $row['price_per_day']; ?>/day</p>
            <p class="text-muted">Rent date: <?php echo $row['rent_date']; ?></p>
            <a href="return_equipment.php?id=<?php echo $row['rent_id']; ?>&e_id=<?php echo $row['equipment_id']?>" class="btn btn-danger">Return Equipment</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>
</body>
</html>
