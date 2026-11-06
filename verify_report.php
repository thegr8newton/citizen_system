<?php
include 'connection.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'authority'){
    header("Location: index.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['action'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if($action == 'resolved'){
        $stmt = $conn->prepare("UPDATE reports SET status='resolved', reward_points=20 WHERE id=?");
        $stmt->bind_param("i",$id);
    } else {
        $stmt = $conn->prepare("UPDATE reports SET status='under_review' WHERE id=?");
        $stmt->bind_param("i",$id);
    }
    $stmt->execute();
    $stmt->close();
}

header("Location: authority_dashboard.php");
exit;
?>