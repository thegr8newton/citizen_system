<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

// ADMIN STATS
$totalUsers = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$totalReports = $conn->query("SELECT COUNT(*) as t FROM reports")->fetch_assoc()['t'];
$resolvedReports = $conn->query("SELECT COUNT(*) as t FROM reports WHERE status='resolved'")->fetch_assoc()['t'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #0f172a;
    color: #e2e8f0;
    font-family: 'Segoe UI', sans-serif;
}

.container-box {
    max-width: 1200px;
    margin: 40px auto;
}

.card {
    background: #1e293b;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

h2, h4 {
    color: white;
}

.table {
    color: white;
}

.table th {
    background: #3b82f6;
    color: white;
}

.btn-custom {
    background: #3b82f6;
    color: white;
}

.logout {
    background: red;
    color: white;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 6px;
}
</style>
</head>

<body>

<div class="container container-box">

<!-- HEADER -->
<div class="d-flex justify-content-between mb-4">
    <h2>Welcome, <?php echo $user['name']; ?> (<?php echo $role; ?>)</h2>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- ================= CITIZEN ================= -->
<?php if($role == 'citizen'): ?>

<div class="card">
    <h4>Submit Report</h4>

    <form action="report_submit.php" method="post">
        <input class="form-control mb-2" name="issue_type" placeholder="Issue Type" required>
        <input class="form-control mb-2" name="location" placeholder="Location" required>
        <textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea>

        <button class="btn btn-custom" name="submit_report">Submit</button>
    </form>
</div>

<div class="card">
    <h4>Your Reports</h4>

    <?php
    $uid = $user['id'];
    $reports = $conn->query("SELECT * FROM reports WHERE user_id=$uid ORDER BY created_at DESC");
    ?>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Issue</th>
            <th>Status</th>
            <th>Points</th>
        </tr>

        <?php while($r = $reports->fetch_assoc()): ?>
        <tr>
            <td><?php echo $r['id']; ?></td>
            <td><?php echo $r['issue_type']; ?></td>
            <td><?php echo $r['status']; ?></td>
            <td><?php echo $r['reward_points']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php endif; ?>

<!-- ================= AUTHORITY ================= -->
<?php if($role == 'authority'): ?>

<div class="card">
    <h4>Manage Reports</h4>

    <?php
    $reports = $conn->query("
        SELECT r.*, u.name 
        FROM reports r 
        JOIN users u ON r.user_id=u.id 
        ORDER BY r.created_at DESC
    ");
    ?>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Citizen</th>
            <th>Issue</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($r = $reports->fetch_assoc()): ?>
        <tr>
            <td><?php echo $r['id']; ?></td>
            <td><?php echo $r['name']; ?></td>
            <td><?php echo $r['issue_type']; ?></td>
            <td><?php echo $r['status']; ?></td>
            <td>

                <a class="btn btn-warning btn-sm"
                   href="verify_report.php?id=<?php echo $r['id']; ?>&action=under_review">
                   Review
                </a>

                <a class="btn btn-success btn-sm"
                   href="verify_report.php?id=<?php echo $r['id']; ?>&action=resolved">
                   Resolve
                </a>

            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php endif; ?>

<!-- ================= ADMIN ================= -->
<?php if($role == 'admin'): ?>

<div class="row mb-3">

    <div class="col-md-4">
        <div class="card text-center bg-primary">
            <h5>Total Users</h5>
            <h2><?php echo $totalUsers; ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center bg-warning text-dark">
            <h5>Total Reports</h5>
            <h2><?php echo $totalReports; ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center bg-success">
            <h5>Resolved</h5>
            <h2><?php echo $resolvedReports; ?></h2>
        </div>
    </div>

</div>

<div class="card">
    <h4>Manage Users</h4>

    <?php
    $users = $conn->query("SELECT * FROM users ORDER BY role");
    ?>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>

        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo $u['name']; ?></td>
            <td><?php echo $u['email']; ?></td>
            <td><?php echo $u['role']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php endif; ?>

</div>

</body>
</html>