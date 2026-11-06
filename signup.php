<?php
include 'connection.php';

$message = "";

if(isset($_POST['register'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        $message = "User already exists!";
    } else {
        $conn->query("INSERT INTO users (email, password, role) VALUES ('$email','$hashed_password','$role')");
        $message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card-box {
            width: 420px;
            background: #1e293b;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #ffffff !important;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #334155 !important;
            color: white !important;
        }

        input::placeholder {
            color: #cbd5e1;
        }

        .btn-custom {
            width: 100%;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #2563eb;
        }

        .message {
            background: rgba(255,255,255,0.08);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            color: #f1f5f9;
        }

        a {
            color: #38bdf8;
            text-decoration: none;
        }

        .footer-text {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="card-box">

    <h2>Sign Up</h2>

    <?php if($message) echo "<div class='message'>$message</div>"; ?>

    <form method="post">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>Role</label>
        <select name="role">
            <option value="citizen">Citizen</option>
            <option value="authority">Authority</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="register" class="btn-custom">
            Register
        </button>

    </form>

    <div class="footer-text">
        Already have an account? <a href="index.php">Login</a>
    </div>

</div>

</body>
</html>