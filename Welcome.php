<?php
    session_start();
    require "Db.php";
    $Hasil = 0;

    if(!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
        header("Location: Login.php");
        exit;
    }

    $Sql = 'SELECT Jumlah FROM transaksi WHERE Id_user = :id';

    $stmt = $conn -> prepare($Sql);
    $stmt -> bindParam(":id", $_SESSION['ID_User'], PDO::PARAM_STR);
    $stmt -> execute();
    while($Data = $stmt -> fetch()){
        $Hasil += $Data['Jumlah'];
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
    <h1 class="my-5">Hola! <b><?php echo htmlspecialchars($_SESSION['Username']); ?></b>. Selamat datang di tabungin.</h1>
    <h2 class="my-5"> Saldomu: <?= $Hasil ?></h2>
    <p>
        <a href="updateAkun.php" class="btn btn-warning">Akun</a>
        <a href="Tambah catatan.php" class="btn btn-outline-success">Tambah catatan</a>
        <a href="Logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <p>
        <a href="History.php">Liat sejarah</a>
    </p>
</body>
</html>