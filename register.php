<?php
session_start();
require_once "config.php";

$username = $password = $confirm_password = $email = $captcha = "";
$username_err = $password_err = $confirm_password_err = $email_err = $captcha_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);

    // Check for password
    if (empty(trim($_POST['password']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['password'])) < 5) {
        $password_err = "Password cannot be less than 5 characters";
    } else {
        $password = trim($_POST['password']);
    }

    // Check for confirm password field
    if (trim($_POST['password']) != trim($_POST['confirm_password'])) {
        $confirm_password_err = "Passwords should match";
    }
    $phone = "";
    $phone_err = "";
    
    // Validate phone number
    if (isset($_POST['phone'])) {
        if (empty(trim($_POST['phone']))) {
            $phone_err = "Phone number cannot be blank";
        } elseif (!preg_match("/^[0-9]{10}$/", trim($_POST['phone']))) {
            $phone_err = "Invalid phone number format";
        } else {
            $phone = trim($_POST['phone']);
        }
    }
    //validation of Phone number 
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($captcha_err) && empty($phone_err)) {
      // Rest of the code for inserting data into the database
  }
  
    // Check if 'email' key exists in $_POST array
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
    } else {
        $email_err = "Email is not provided";
    }
    
   
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // ...
    
        // Check if the captcha form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve the user's captcha input
            $captcha_input = $_POST['captcha'];
    
            // Check if the captcha input matches the stored captcha text
            if (strcasecmp($captcha_input, $_SESSION['captcha_text']) !== 0) {
                $captcha_err = "Captcha value is incorrect";
            }
    
            // Clear the captcha session variable
            unset($_SESSION['captcha_text']);
    
            // Rest of the code for processing the form submission
            // ...
        }
    }
    // Function to generate a random captcha text
function generateCaptchaText($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha_text = '';

    for ($i = 0; $i < $length; $i++) {
        $captcha_text .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $captcha_text;
}

// Generate a new captcha text if it doesn't exist in the session
if (!isset($_SESSION['captcha_text'])) {
    $_SESSION['captcha_text'] = generateCaptchaText();
}

$captcha_text = $_SESSION['captcha_text'];
$captcha_prompt = "Please enter the following characters: " . $captcha_text;


    // If there were no errors, go ahead and insert into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($captcha_err)) {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);

            // Set these parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;

            // Try to execute the query
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit();
            } else {
                echo "Something went wrong... cannot redirect!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        .wrapper {
            width: 360px;
            padding: 20px;
            margin: 0 auto;
        }

        .wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .wrapper p {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .help-block {
            color: red;
        }

        .btn-primary,
        .btn-default {
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .captcha-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .captcha-text {
            margin-right: 10px;
            font-weight: bold;
            font-size: 18px;
            background-color: #f2f2f2;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .refresh-captcha {
            padding: 6px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .refresh-captcha:hover {
            background-color: #0056b3;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-default {
            background-color: #ccc;
            color: #000;
        }

        p {
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Register Here!</h2>
        <p>Please fill out this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
    <label>Phone Number:</label>
    <input type="text" name="phone" class="form-control" value="<?php echo isset($phone) ? $phone : ''; ?>">
    <span class="help-block"><?php echo isset($phone_err) ? $phone_err : ''; ?></span>
</div>

            <div class="form-group <?
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($captcha_err)) ? 'has-error' : ''; ?>">
        <label>Captcha:</label>
        <div class="captcha-container">
            <span class="captcha-text"><?php echo isset($captcha_text) ? $captcha_text : ''; ?></span>
            <button type="button" class="refresh-captcha" onclick="refreshCaptcha()">Refresh</button>
        </div>
        <input type="text" name="captcha" class="form-control" value="">
        <span class="help-block"><?php echo isset($captcha_err) ? $captcha_err : ''; ?></span>
    </div>
    

 <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div><script>
        function refreshCaptcha() {
            // Reload the page to generate a new captcha text
            location.reload();
        }
    </script>
    
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>

<script>
    function refreshCaptcha() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('.captcha-text').textContent = this.responseText;
            }
        };
        xhttp.open('GET', 'refresh_captcha.php', true);
        xhttp.send();
    }
</script>
</body>
</html>