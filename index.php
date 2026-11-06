<?php
include 'connection.php';
$error = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='$role'");
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['user'] = $user;
            if($role=='citizen') header("Location: citizen_dashboard.php");
            elseif($role=='authority') header("Location: authority_dashboard.php");
            elseif($role=='admin') header("Location: admin_dashboard.php");
            exit;
        } else { $error = "Wrong password!"; }
    } else { $error = "User not found!"; }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Citizen System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .login-container {
            width: 400px;
            background: rgba(255,255,255,0.95);
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.6s ease;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2d2d2d;
            font-weight: 600;
        }

        label {
            display: block;
            margin-top: 12px;
            font-size: 13px;
            color: #444;
        }

        input, select {
            width: 100%;
            padding: 11px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }

        input:focus, select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
        }

        button {
            width: 100%;
            margin-top: 18px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .error {
            background: #ffe5e5;
            color: #d10000;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
            font-size: 13px;
        }

        p {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        a button {
            background: #222;
            margin-top: 10px;
        }

        a button:hover {
            background: #000;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<div class="login-container">

    <h2>Citizen Reporting System</h2>

    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <form method="post">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role">
            <option value="citizen">Citizen</option>
            <option value="authority">Authority</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="login">Login</button>

        <p>Don't have an account?</p>

        <a href="signup.php">
            <button type="button">Sign Up</button>
        </a>

    </form>

</div>

</body>
</html>