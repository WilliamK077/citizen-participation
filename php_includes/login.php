<?php
session_start();
require 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        switch ($user['role']) {
            case 'citizen':
                header('Location: welcome.php'); // Citizens go to feedback and voting
                break;
            case 'official':
                header('Location: government_dashboard.php'); // Officials go to poll creation
                break;
            case 'moderator':
                header('Location: moderation_dashboard.php'); // Moderators go to a moderation dashboard
                break;
            default:
                header('Location: login.php'); // Fallback in case of an unknown role
        }
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Natuseko Civil Platform </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Main container styles */
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: blue;
            color: white;
        }

        .form-body {
            color: white;
            background-color: transparent;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 400px;
            background: url("img.jpg");
            background-size: cover;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: blue;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container p {
            margin-top: 15px;
        }

        /* Password container with icon */
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            padding-right: 30px;
        }

        .password-container i {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: #999;
        }
    </style>
</head>
<body>

<div class="main-container bg-light center-items">
    <div class="form-body">
        <div class="form-container">
            <h2>Login To Your <br> <span class="highlight">Natuseko Civil Platform</span> Account</h2>
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fa fa-eye-slash" id="togglePassword"></i> <!-- Toggle icon -->
                </div>
                <button type="submit" class="btn-submit">Login</button>
            </form>
            <div class="form-error">
                <!-- Error message here -->
                <?php echo $error ?>
            </div>
            <p>
                Don't have an account? <a href="register.php">Register</a>
            </p>
            <p><a href="../index.html">Home</a></p>
        </div>
    </div>
</div>

<script>
    // JavaScript for toggling password visibility
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const icon = this;

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
</script>

</body>
</html>

</body>
</html>