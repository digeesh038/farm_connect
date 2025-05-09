<?php
session_start();
if (!isset($_SESSION['farmer_id'])) {
    header('Location: auth\FarmerLogin.php');
    exit();
}

session_start();
include('../Includes/db.php');

// Fetch users
$users = $conn->query("SELECT * FROM farmers");

// Fetch equipment
$equipment = $conn->query("SELECT * FROM equipment");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>

<h2>Users (Farmers)</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $users->fetch_assoc()): ?>
    <tr>
        <td><?= $row['farmer_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><a href="delete_user.php?id=<?= $row['farmer_id'] ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Equipment</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Status</th>
        <th>Owner (Farmer ID)</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $equipment->fetch_assoc()): ?>
    <tr>
        <td><?= $row['equipment_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['type'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['farmer_id'] ?></td>
        <td>
            <?php if ($row['status'] == 'pending'): ?>
                <a href="approve_equipment.php?id=<?= $row['equipment_id'] ?>">Approve</a>
            <?php endif; ?>
            <a href="delete_equipment.php?id=<?= $row['equipment_id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="admin_logout.php">Logout</a>

</body>
</html>
