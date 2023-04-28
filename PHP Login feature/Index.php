<?php
    require_once "Db.php";

    $Username = $Password = $Confirmpasword = "";
    $Username_err = $Password_err = $Confirmpasword_err = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty(trim($_POST['Username']))) {
            $Username_err = "Harap isi nama anda";
        }
        elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['Username']))) {
            $Username_err = "Hanya huruf, angka, dan underscores!!";
        }
        else{
            $Sql = "Select ID_User FROM tabungin_user WHERE Username = :Username";

            if($stmt = $conn -> prepare($Sql)) {
                // Bind variable to prepared statement
                $stmt -> bindParam(":Username", $param_Username, PDO::PARAM_STR);

                $param_Username = trim($_POST['Username']);

                if($stmt -> execute()) {
                    if($stmt -> rowCount() == 1) {
                        $Username_err = "Nama ini sudah ada, coba cari nama lain";
                    }else {
                        $Username = trim($_POST['Username']);
                    }
                }else {
                    echo "Coba lagi nanti";
                }
            }

            unset($stmt); 
        }

        if(empty(trim($_POST['Password']))) {
            $Password_err = "Tolong masukkan password";
        }elseif(strlen(trim($_POST['Password'])) < 8) {
            $Password_err = "Password harus 8 digit";   
        }else {
            $Password = trim($_POST['Password']);
        }

        if(empty(trim($_POST['Confirmpasword']))) {
            $Confirmpasword_err = "Please, confirm your password";
        }else {
            $Confirmpasword = trim($_POST['Confirmpasword']);
            if(empty($Password_err) && ($Password != $Confirmpasword)) {
                $Confirmpasword_err = "Password tidak sesuai, harap masukkan ulang";
            }
        }

        if(empty($Username_err) && empty($Password_err) && empty($Confirmpasword_err)) {

            $Sql = "INSERT INTO tabungin_user (Username, Password) VALUES (:Username, :Password)";

            if($stmt = $conn -> prepare($Sql)) {

                $stmt -> bindParam(":Username", $param_Username, PDO::PARAM_STR);
                $stmt -> bindParam(":Password", $param_Password, PDO::PARAM_STR);

                $param_Username = $Username;
                $param_Password = password_hash($Password, PASSWORD_DEFAULT);

                if($stmt -> execute()) {

                    header("Location: Login.php");
                }
                else {
                    echo "Something went wrong, please try again later";
                }
    
                mysqli_stmt_close($stmt);   
            }

        }
        unset($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="Username" class="form-control <?php echo (!empty($Username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Username; ?>">
                <span class="invalid-feedback"><?php echo $Username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="Password" class="form-control <?php echo (!empty($Password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Password; ?>">
                <span class="invalid-feedback"><?php echo $Password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="Confirmpasword" class="form-control <?php echo (!empty($Confirmpasword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Confirmpasword; ?>">
                <span class="invalid-feedback"><?php echo $Confirmpasword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>