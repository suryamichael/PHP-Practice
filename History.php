<?php
    session_start();
    require_once "Db.php";
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
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                    <div class="row">
                        <div class="col">
                        <h2 style="text-align: center;">History transaksi</h2>
                    </div>
                </div>
                <th scope="col">Nomer</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Kategori</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Tanggal</th>
            </tr>
        </thead>
        <?php
            $Sql = 'SELECT * FROM transaksi WHERE Id_user = :id';
            $no = 0;

            $stmt = $conn -> prepare($Sql);
            $stmt -> bindParam(":id", $_SESSION['ID_User'], PDO::PARAM_STR);
            $stmt -> execute();
            while($Data = $stmt -> fetch()){
                $no++;
        ?>
        <tr>
            <td><?= $no ?></td>
            <td><?= $Data['Nama'] ?></td>
            <td><?= $Data['Kategori'] ?></td>
            <td><?= $Data['Jumlah'] ?></td>
            <td><?= $Data['Tgl'] ?></td>
        </tr>
        <?php }?>
    </table>
    <a href = "Welcome.php">Kembali ke masa muda</a>
</body>
</html>