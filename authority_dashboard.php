<?php
session_start();
include 'connection.php';

// Protect page
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'authority'){
    header("Location: index.php");
    exit;
}

// Stats
$total = $conn->query("SELECT COUNT(*) as total FROM reports")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='pending'")->fetch_assoc()['total'];
$review = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='under_review'")->fetch_assoc()['total'];
$resolved = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='resolved'")->fetch_assoc()['total'];

// Filter
$status = isset($_GET['status']) ? $_GET['status'] : '';

if($status != ''){
    $reports = $conn->query("
        SELECT r.*, u.name 
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.status='$status'
        ORDER BY r.created_at DESC
    ");
} else {
    $reports = $conn->query("
        SELECT r.*, u.name 
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authority Dashboard</title>

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
            border: none;
            border-radius: 12px;
            padding: 15px;
        }

        .table {
            color: white;
        }

        .table th {
            background: #3b82f6;
            color: white;
        }

        .btn-action {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-review { background: #f59e0b; }
        .btn-resolve { background: #22c55e; }

        .status {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: white;
        }

        .pending { background: gray; }
        .under_review { background: orange; }
        .resolved { background: green; }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="container container-box">

    <!-- HEADER -->
    <div class="header">
        <h2>Authority Dashboard</h2>
        <div>
            Welcome, <?php echo $_SESSION['user']['name']; ?> |
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <h5>Total Reports</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-secondary text-white">
                <h5>Pending</h5>
                <h2><?php echo $pending; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-warning text-dark">
                <h5>Under Review</h5>
                <h2><?php echo $review; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <h5>Resolved</h5>
                <h2><?php echo $resolved; ?></h2>
            </div>
        </div>

    </div>

    <!-- FILTER -->
    <form method="GET" class="mb-3">
        <select name="status" class="form-select w-25" onchange="this.form.submit()">
            <option value="">All Reports</option>
            <option value="pending">Pending</option>
            <option value="under_review">Under Review</option>
            <option value="resolved">Resolved</option>
        </select>
    </form>

    <!-- TABLE -->
    <div class="card">

        <h4>Reports Management</h4>

        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Citizen</th>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = $reports->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['issue_type']; ?></td>
                    <td>
                        <span class="status <?php echo $row['status']; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>

                        <?php if($row['status'] != 'resolved'): ?>

                            <a class="btn-action btn-review"
                               href="verify_report.php?id=<?php echo $row['id']; ?>&action=under_review">
                                Review
                            </a>

                            <a class="btn-action btn-resolve"
                               href="verify_report.php?id=<?php echo $row['id']; ?>&action=resolved">
                                Resolve
                            </a>

                        <?php else: ?>
                            Done
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>