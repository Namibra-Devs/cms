<?php
ob_start();
session_start();
include('./database/config.php');
$error_message='';

// check if login button is clicked
if(isset($_POST['login'])) {
    
	// check whether email and password fields are not empty before passing data
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = 'Email and/or Password can not be empty<br>';
    } else {
		
		$email = strip_tags($_POST['email']);
		$password = strip_tags($_POST['password']);

    	$statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=? AND status=?");
    	$statement->execute(array($email,'Active'));
    	$total = $statement->rowCount();    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
        if($total==0) {
            $error_message .= 'Email Address does not match<br>';
        } else {       
            foreach($result as $row) { 
                $row_password = $row['password'];
            }
        
            if( $row_password != md5($password) ) {
                $error_message .= 'Password does not match<br>';
            } else {       
            
				$_SESSION['user'] = $row;
                header("location: index.php");
            }
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Oswald:wght@300;600;700&family=Raleway:wght@300;700&family=Source+Sans+Pro:wght@300;400;600;700&family=Source+Serif+Pro:wght@300;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin | login</title>
</head>

<body>
    <div class="login-container">
        <div class="loginbox">
            <div class="login-wrap">
                <h1>Admin Login</h1>
                <p>Access to our dashboard</p>
            </div>
            <p style="color: red; padding-bottom:1rem" >
                <?php echo $error_message; ?>
            </p>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" type="text" name="email" id="email">
                </div>
                <div class="form-group">
                    <label for="email">Password:</label>
                    <input class="form-control" type="password" name="password" id="password">
                </div>
                <div class="form-group submit">
                    <input class="login-submit" type="submit" name="login">
                    <!-- <a href="./dashboard.php" class="login-submit">Login</a> -->
                </div>
                <div class="form-group align-right">
                    <a class="forgot-password" href="#">Forgot your password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>