<?php
    require_once "Db.php";

    $Username = $Password = $Confirmpasword = $Email = $Nohp = "";
    $Username_err = $Password_err = $Confirmpasword_err = $Email_err = $Nohp_err = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty(trim($_POST['Username']))){
            $Username_err = "Harap isi email anda";
        }elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['Username']))) {
            $Username_err = "Hanya huruf, angka, dan underscores!!";
        }else{
            $Username = trim($_POST['Username']);
        }

        //Check the fill is empty or not
        if(empty(trim($_POST['Email']))) {
            $Email_err = "Harap isi Email anda";
        } //Check the username is have any symbol
        
        else{
            // The statement based Uesrname in the row 1
            $Sql = "Select ID_User FROM tabungin_user WHERE Email = :Email";

            if($stmt = $conn -> prepare($Sql)) {
                // Bind variable to prepared statement
                $stmt -> bindParam(":Email", $param_Email, PDO::PARAM_STR);

                $param_Email = trim($_POST['Email']);

                if($stmt -> execute()) {
                    // if the Email found in the row 1
                    if($stmt -> rowCount() == 1) {
                        $Email_err = "Email ini sudah ada, coba cari Email lain";
                    }
                    else {
                        $Email = trim($_POST['Email']);
                    }
                }else {
                    echo "Coba lagi nanti";
                }
            }

            unset($stmt); 
        }

        
        if(!preg_match('/^[0-9]+$/', trim($_POST['Nomerhp']))){
            $Nohp_err = "Harap hanya masukkan angka saja";
        }else{
            $Nohp = trim($_POST['Nomerhp']);
        }

        //Check the password length is more than 8 or not
        if(empty(trim($_POST['Password']))) {
            $Password_err = "Tolong masukkan password";
        }elseif(strlen(trim($_POST['Password'])) < 8) {
            $Password_err = "Password harus 8 digit";   
        }else {
            $Password = trim($_POST['Password']);
            $Hashpassword = password_hash($Password, PASSWORD_DEFAULT);
        }

        // Check the confirmation password as same as the password
        if(empty(trim($_POST['Confirmpasword']))) {
            $Confirmpasword_err = "Please, confirm your password";
        }else {
            $Confirmpasword = trim($_POST['Confirmpasword']);
            if(empty($Password_err) && ($Password != $Confirmpasword)) {
                $Confirmpasword_err = "Password tidak sesuai, harap masukkan ulang";
            }
        }

        //Check if all the form is filled succesfully
        if(empty($Username_err) && empty($Password_err) && empty($Confirmpasword_err)) {
            
            $Sql = "INSERT INTO tabungin_user (Username, Email, Nomer_hp, Password) VALUES (:Username, :Email, :Nomer_hp ,:Password)";

            if($stmt = $conn -> prepare($Sql)) {
                
                $params =  array (
                    ':Username' => $Username,
                    ':Email' => $Email,
                    ':Nomer_hp' => $Nohp,
                    ':Password' => $Hashpassword,
                );

                try {
                    $stmt -> execute($params);
                    header("Location: Login.php");
                } catch (PDOException $e) {
                    echo "Coba lagi nanti";
                }

                /* $stmt -> bindParam(":Username", $param_Username, PDO::PARAM_STR);
                $stmt -> bindParam(":Password", $param_Password, PDO::PARAM_STR);

                $param_Username = $Username;
                $param_Password = password_hash($Password, PASSWORD_DEFAULT);

                if($stmt -> execute()) {

                    header("Location: Login.php");
                }
                else {
                    echo "Something went wrong, please try again later";
                } */
    
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
                <label>Email</label>
                <input type="text" name="Email" class="form-control <?php echo (!empty($Email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
                <span class="invalid-feedback"><?php echo $Email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nomer HP</label>
                <input type="text" name="Nomerhp" class="form-control <?php echo (!empty($Nohp_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Nohp; ?>">
                <span class="invalid-feedback"><?php echo $Nohp_err; ?></span>
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