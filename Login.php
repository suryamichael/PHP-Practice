<?php
    session_start();

    if(isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] === true) {
        header("Location: Welcome.php");
        exit;
    }

    require_once "Db.php";

    $Email = $Password = "";
    $Email_err = $Password_err = $Login_err = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty(trim($_POST['Email']))) {
            $Email_err = "Masukkan Email anda";
        }
        else {
            $Email = trim($_POST['Email']);
        }

        if(empty(trim($_POST['Password']))) {
            $Password_err = "Masukkan Password anda";
        }
        else {
            $Password = trim($_POST['Password']);
        }

        if(empty($Email_err) && empty($Password_err)) {
            
            $Sql = "SELECT ID_User, Username, Email, Password FROM tabungin_user WHERE Email = :Email";

            if($stmt = $conn -> prepare($Sql)) {
                
                $stmt -> bindParam(":Email", $Param_Email, PDO::PARAM_STR);
                $Param_Email= trim($_POST['Email']);

                if($stmt -> execute()) {
                    if($stmt -> rowCount() == 1) {
                        if($row = $stmt ->fetch()) {
                            $Id = $row['ID_User'];
                            $Username = $row['Username'];
                            $Email = $row['Email'];
                            $Hashed_password = $row['Password'];
                            

                            if(password_verify($Password, $Hashed_password)) {
                                session_start();

                                $_SESSION['Loggedin'] = true;
                                $_SESSION['ID_User'] = $Id;
                                $_SESSION['Username'] = $Username;
                                $_SESSION['Email'] = $Email;

                                header("Location: Welcome.php");
                            }else {
                                $Login_err = "Salah password";
                            }
                        }
                    }
                    else {
                        $Login_err = "Salah Email";
                    }
                }
                else{
                    echo "Silahkan coba lagi nanti, ada error dikit";
                }

                unset($stmt);
            }
        }
        
        unset($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($Login_err)){
            echo '<div class="alert alert-danger">' . $Login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="Email" class="form-control <?php echo (!empty($Email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
                <span class="invalid-feedback"><?php echo $Email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="Password" class="form-control <?php echo (!empty($Password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $Password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="Index.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>