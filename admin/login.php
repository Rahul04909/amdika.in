<?php
session_start();
require_once '../database/db_config.php';

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Password to verify

    $sql = "SELECT id, username, password FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Amadika</title>
    <!-- Content Security Policy: Strict, no external scripts -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">
    <!-- Bootstrap 5 CSS (Local) -->
    <link href="../assets/vendor/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (Local CSS) -->
    <link rel="stylesheet" href="../assets/vendor/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/amdika-logo.png">
    <!-- Custom CSS -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Rubik', sans-serif;
            background-color: #f8f9fa;
        }
        
        .login-container {
            height: 100vh;
            display: flex;
            overflow: hidden;
        }
        
        /* Left Side - Image */
        .login-image-side {
            flex: 1;
            background: url('../assets/images/demo-data/product.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Overlay for text readability on image */
        .login-image-side::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        
        .login-quote {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .login-quote h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .login-quote p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* Right Side - Form */
        .login-form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-color: #fff;
            max-width: 600px; /* Prevent overly wide form on huge screens */
            width: 100%; 
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        
        .brand-logo-login {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo-login img {
            max-height: 60px;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2D3436;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .login-subtitle {
            color: #636E72;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.2);
            border-color: #D4A017;
        }
        
        .password-group {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #636E72;
            z-index: 10;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background-color: #D32F2F; /* Primary Color */
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1.1rem;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            background-color: #B71C1C;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .login-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .login-image-side {
                height: 250px; /* Short header image on mobile */
                flex: none;
            }
            
            .login-quote {
                display: none; /* Hide quote on mobile to save space */
            }
            
            .login-form-side {
                max-width: 100%;
                flex: 1;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Side -->
        <div class="login-image-side">
            <div class="login-quote">
                <h2>Welcome Back</h2>
                <p>Manage your store effectively and efficiently with Amadika Admin Dashboard.</p>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-form-side">
            <div class="login-wrapper">
                <div class="brand-logo-login">
                    <img src="../assets/images/amdika-logo.png" alt="Amadika Logo">
                </div>
                
                <h3 class="login-title">Admin Login</h3>
                <p class="login-subtitle">Enter your credentials to access your account</p>
                
                <?php if($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group password-group">
                             <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Enter password" required>
                            <span class="toggle-password" onclick="togglePassword()">
                                <i class="far fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Login</button>
                    
                    <div class="text-center mt-3">
                        <a href="#" class="text-muted small text-decoration-none">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <!-- Bootstrap JS (Local) -->
    <script src="../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
