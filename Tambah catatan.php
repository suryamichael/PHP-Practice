<?php
    session_start();
    require_once "Db.php";

    if(!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
        header("Location: Login.php");
        exit;
    }

    $Keterangan = $Kategori = $Jumlah = $Tanggal = "";
    $Keterangan_err = $Kategori_err = $Jumlah_err = $Tanggal_err = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(empty(trim($_POST['Keterangan']))){
            $Keterangan_err = "Harus Diisi";
        }else if(!preg_match('/^[a-zA-Z0-9]+$/', trim($_POST['Keterangan']))){
            $Keterangan_err = "Hanya huruf, dan angka saja yang dimasukkan";
        }else{
            $Keterangan = $_POST['Keterangan'];
        }

        if(empty(trim($_POST['Jumlah']))){
            $Jumlah_err = "Harap isi nominal anda";
        }else if(!preg_match('/^[0-9]+$/', trim($_POST['Jumlah']))){
            $Jumlah_err = "Hanya angka yang boleh dimasukkan";
        }else{
            $Jumlah = $_POST['Jumlah'];
        }

        if(empty(trim($_POST['Kategori']))){
            $Kategori_err = "Wajib diisi";
        }else {
            $Kategori = $_POST['Kategori']; 
            if($Kategori == 2){
                $Jumlah = -1 * $Jumlah;
            }
        }

        if(empty(trim($_POST['Tanggal']))){
            $Tanggal_err = "Wajib diisi";
        }else {
            $Tanggal = $_POST['Tanggal']; 
        }

        if(empty($Keterangan_err) && empty($Jumlah_err) && empty($Kategori_err) && empty($Tanggal_err)){
            global $conn;

            $Sql = "INSERT INTO transaksi (Id_transaksi, Nama, Kategori, Jumlah, Tgl, Id_user)
                    VALUES (:Id_transaksi, :Nama, :Kategori, :Jumlah, :Tgl, :Id_user)";
            
            $bindParams = array(
                'Id_transaksi' => 0,
                ':Nama' => $Keterangan,
                ':Kategori' => $Kategori,
                ':Jumlah' => $Jumlah,
                ':Tgl' => $Tanggal,
                'Id_user' => $_POST['Iduser']
            );

            try{
                $stmt = $conn -> prepare($Sql);
                $stmt -> execute($bindParams);
            }catch(PDOException $e){
                echo "Kesalahan menambah laporan". $e -> getMessage();
            }

        }
        unset($Sql);
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
    <div class="wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" value="<?= $_SESSION['ID_User'] ?>" name="Iduser">
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="Keterangan" class="form-control <?php echo (!empty($Keterangan_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Keterangan; ?>">
            <span class="invalid-feedback"><?php echo $Keterangan_err; ?></span>
        </div>    
        <div class="form-group">
            <label>Kategori</label>
            <select name="Kategori" class="form-control <?php echo (!empty($Kategori_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Kategori; ?>">
                <option value="1" <?= ($Kategori == '1' ? "selected" : "")?> >Pemasukkan</option>
                <option value="2" <?= ($Kategori == '2' ? "selected" : "")?>>Pengeluaran</option>
            </select>
            <span class="invalid-feedback"><?php echo $Kategori_err; ?></span>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="text" name="Jumlah" class="form-control <?php echo (!empty($Jumlah_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Jumlah; ?>">
            <span class="invalid-feedback"><?php echo $Jumlah_err; ?></span>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="Date" name="Tanggal" class="form-control <?php echo (!empty($Tanggal_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Tanggal; ?>">
            <span class="invalid-feedback"><?php echo $Tanggal_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
        </div>
    </form>
    <a href="Welcome.php" class="btn btn-warning">Kembali</a>
    </div>
    
</body>
</html>