<?php

session_start();

if($_SESSION['Loggedin'] == false && !isset($_SESSION['Loggedin'])){
    header("Location: Login.php");
    exit;
}

require_once "Db.php";

$Newpassword = $Confirmpassword = "";
$Newpassword_err = $Confirmpassword_err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty(trim($_POST['Newpassword']))){
        $Newpassword_err = "Password baru tidak boleh kosong";
    }else if(strlen(trim($_POST['Newpassword'])) < 8){
        $Newpassword_err = "Jumlah harus lebih dari 8 huruf";
    }else{
        $Newpassword = trim($_POST['Newpassword']);
    }

    if(empty(trim($_POST['Confirmpassword']))){
        $Confirmpassword_err = "Masukkan password dengan benar";
    }else{
        $Confirmpassword = trim($_POST['Confirmpassword']);
        if(empty($Confirmpassword_err) && ($Confirmpassword != $Newpassword)){
            $Confirmpassword_err = "Password tidak sesuai";
        }
    }

    if(empty($Newpassword_err) && empty($Confirmpassword_err)){
        // Mengubah pola password, sehingga tidak dibaca admin
        $Hashpassword = password_hash($Newpassword, PASSWORD_DEFAULT);

        $Sql = 'UPDATE tabungin_user SET Password = :Password WHERE ID_User = :id';

        $Params = array (
            ':Password' => $Hashpassword,
            ':id' => $_SESSION['ID_User'],
        );

        try{
            $stmt = $conn -> prepare($Sql);
            $stmt -> execute($Params);
            session_destroy();
            header("Location: Login.php");
            exit;
        }catch(PDOException $e){
            echo "Try again later" . $e -> getMessage();
        }
        unset($Sql);
    }
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="Newpassword" class="form-control <?php echo (!empty($Newpassword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Newpassword; ?>">
                <span class="invalid-feedback"><?php echo $Newpassword_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="Confirmpassword" class="form-control <?php echo (!empty($Confirmpassword_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $Confirmpassword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
                <a class="btn btn-danger ml-3" href="HapusAkun.php">Hapus Akun</a>
            </div>
        </form>
    </div>    
</body>
</html>