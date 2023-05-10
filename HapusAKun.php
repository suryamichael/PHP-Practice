<?php
    session_start();
    require_once "Db.php";

    $Confirmpassword_err = "";
    $Confrimpassword = "";
    if($_SESSION['Loggedin'] == false && !isset($_SESSION['Loggedin'])){
        header("Location: Login.php");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if(empty(trim($_POST['Confirmpassword']))){
            $Confirmpassword_err = "Harap isi password untuk melanjutkan";
        }else{
            $Confirmpassword = trim($_POST['Confirmpassword']);
        }
    
        if(empty($Confirmpassword_err)){
            $Sql = 'SELECT ID_User, Username, Email, Password FROM tabungin_user WHERE ID_User = :id';
    
            if($stmt = $conn ->prepare($Sql)){
                $stmt -> bindParam(':id', $_SESSION['ID_User'], PDO::PARAM_STR);
                $stmt -> execute();
                    if($stmt -> rowCount() == 1){
                        $Row = $stmt -> fetch();
                        $Password = $Row['Password'];

                        if(password_verify($Confirmpassword, $Password)){
                            $SqlDel = 'DELETE FROM tabungin_user WHERE ID_User = :id';

                            try{
                                $stmt2 = $conn -> prepare($SqlDel);
                                $stmt2 -> bindParam(':id', $_SESSION['ID_User'], PDO::PARAM_STR);
                                $stmt2 -> execute();
                            }catch(PDOException $e){
                                echo "Kesalahan menghapus" .$e ->getMessage();
                                exit;
                            }
                            session_unset();
                            header("Location: Login.php");
                        }else{
                            $Confirmpassword_err = "Kesalahan password";
                        }
                    }
                    unset($SqlDel);
            }
            unset($Sql);
        }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <h2>Hapus Akun</h2>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="Confirmpassword" class="form-control <?php echo (!empty($Confirmpassword_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $Confirmpassword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Hapus">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>