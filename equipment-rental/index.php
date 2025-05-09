<?php include('../Includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Equipment Rental System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Assets/css/style_index.css">

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
      background: rgba(0, 0, 0, 0.7); /* Overlay for better text visibility */
      z-index: -1;
    }

    /* Card animation */
    .card {
      animation: fadeInUp 0.8s ease-in-out;
      transition: transform 0.3s, box-shadow 0.3s;
      border: none;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    /* Button hover effect */
    .card .btn {
      transition: background-color 0.3s, transform 0.2s;
    }

    .card .btn:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }
    .card-img-top {
      width: 100%; /* Ensures the image spans the full width of the card */
      height: 300px; /* Set a fixed height for all images */
      object-fit: cover; /* Ensures the image covers the space while maintaining its aspect ratio */
      border-radius: 4px; /* Optional: Adds rounded corners to the images */
    }

    /* Fade-in animation for cards */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center mb-4 text-white">Equipments</h2>

  <nav id="sidebar" class="menu-open">
    <ul>
       <li><a href="dashboard.php" class="active">Dashboard</a></li>
       <li><a href="index.php">Equipments</a></li>
       <li><a href="add_equipment.php">Renting Out</a></li>
    </ul>
  </nav>
  <div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

  <div class="row">
    <?php
    $result = mysqli_query($con, "SELECT * FROM equipment WHERE status = 'available'");
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
      <div class="col-md-4">
        <div class="card mb-4 shadow">
          <img src="Assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['name']; ?></h5>
            <p class="card-text"><?php echo $row['description']; ?></p>
            <p class="text-success fw-bold">₹<?php echo $row['price_per_day']; ?>/day</p>
            <a href="rent_equipment.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Rent Now</a>
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
