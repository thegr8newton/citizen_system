<?php
include 'connection.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'citizen'){
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Reports
$reports = $conn->query("SELECT * FROM reports WHERE user_id=$user_id ORDER BY created_at DESC");

// Stats
$total = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id=$user_id")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id=$user_id AND status='pending'")->fetch_assoc()['total'];
$review = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id=$user_id AND status='under_review'")->fetch_assoc()['total'];
$resolved = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id=$user_id AND status='resolved'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Citizen Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #0f172a;
    color: #e2e8f0;
    font-family: 'Segoe UI', sans-serif;
}

/* CONTAINER */
.container-box {
    max-width: 1200px;
    margin: 40px auto;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.logout-btn {
    background: #ef4444;
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
}

.logout-btn:hover {
    background: #dc2626;
}

/* CARD */
.card {
    background: #1e293b;
    border: none;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
}

/* HEADINGS FIX (IMPORTANT) */
h2, h3, h4 {
    color: #ffffff !important;
    font-weight: 600;
}

.card h4 {
    color: #38bdf8 !important;
    margin-bottom: 15px;
}

/* LABELS FIX */
label {
    font-weight: 600;
    color: #ffffff !important;
    display: block;
    margin-bottom: 6px;
}

/* INPUTS */
input, select, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    outline: none;
    background: #334155 !important;
    color: white !important;
    margin-bottom: 12px;
}

input::placeholder,
textarea::placeholder {
    color: #cbd5e1;
}

/* BUTTON */
.btn-custom {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 8px;
    transition: 0.3s;
}

.btn-custom:hover {
    background: #2563eb;
}

/* STATS */
.stat-box {
    text-align: center;
    padding: 20px;
    border-radius: 12px;
    color: white;
}

.bg-primary { background: #3b82f6 !important; }
.bg-secondary { background: #64748b !important; }
.bg-warning { background: #f59e0b !important; color: black !important; }
.bg-success { background: #22c55e !important; }

/* TABLE */
table {
    width: 100%;
    color: white;
}

th {
    background: #3b82f6;
    padding: 10px;
}

td {
    padding: 10px;
}

tr:nth-child(even) {
    background: #334155;
}

/* STATUS */
.status {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    color: white;
}

.pending { background: gray; }
.under_review { background: orange; }
.resolved { background: green; }

</style>
</head>

<body>

<div class="container container-box">

    <!-- HEADER -->
    <div class="header">
        <h2>Citizen Dashboard</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card stat-box bg-primary">
                <h5>Total Reports</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-box bg-secondary">
                <h5>Pending</h5>
                <h2><?php echo $pending; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-box bg-warning">
                <h5>Under Review</h5>
                <h2><?php echo $review; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-box bg-success">
                <h5>Resolved</h5>
                <h2><?php echo $resolved; ?></h2>
            </div>
        </div>

    </div>

    <!-- SUBMIT REPORT -->
    <div class="card">
        <h4>Submit Report</h4>

        <form action="report_submit.php" method="post" enctype="multipart/form-data">

            <label>Issue Type</label>
            <select name="issue_type" required>
                <option>Pothole</option>
                <option>Broken Streetlight</option>
                <option>Water Leakage</option>
                <option>Illegal Dumping</option>
                <option>Other</option>
            </select>

            <label>Location</label>
            <input type="text" name="location" placeholder="Enter location" required>

            <label>Description</label>
            <textarea name="description" placeholder="Describe the issue..." required></textarea>

            <label>Upload Image</label>
            <input type="file" name="image">

            <button type="submit" name="submit_report" class="btn-custom">
                Submit Report
            </button>

        </form>
    </div>

    <!-- REPORTS -->
    <div class="card">
        <h4>Your Reports</h4>

        <table class="table table-hover mt-3">
            <tr>
                <th>ID</th>
                <th>Issue</th>
                <th>Status</th>
                <th>Reward Points</th>
            </tr>

            <?php while($row = $reports->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['issue_type']; ?></td>
                <td>
                    <span class="status <?php echo $row['status']; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td><?php echo $row['reward_points']; ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</body>
</html>