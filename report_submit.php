<?php
include 'connection.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'citizen'){
    header("Location: index.php");
    exit;
}

if(isset($_POST['submit_report'])){
    $user_id = $_SESSION['user']['id'];
    $issue_type = $_POST['issue_type'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $image = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image = 'images/'.time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $conn->prepare("INSERT INTO reports (user_id, issue_type, location, description, image) VALUES (?,?,?,?,?)");
    $stmt->bind_param("issss",$user_id,$issue_type,$location,$description,$image);
    $stmt->execute();
    $stmt->close();

    header("Location: citizen_dashboard.php");
}
?>