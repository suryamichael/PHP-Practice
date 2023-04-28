<?php
    session_start();

    if(!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
        header("Location: Login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hola! <b><?php echo htmlspecialchars($_SESSION["Username"]); ?></b>. Selamat datang di tabungin.</h1>
    <p>
        <a href="#" class="btn btn-warning">Reset Your Password</a>
        <a href="Logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>
</html>