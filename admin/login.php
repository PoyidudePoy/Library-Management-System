<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Username & Password Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f4f4f4, #e0e0e0);
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 900px;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .branding {
            flex: 1;
            text-align: center;
            padding-right: 40px;
        }

        .branding .logo {
            max-width: 50%;
            height: auto;
            margin-bottom: 20px;
        }

        .branding p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }

        .login-container {
            flex: 1;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #128b02;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.2);
        }

        .password-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .password-container input {
            flex: 1;
        }

        .show-password {
            font-size: 12px;
            color: #555;
            margin-left: 10px;
            display: flex;
            align-items: center;
        }

        .show-password input {
            margin-right: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #128b02;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .form-group button:hover {
            background: #00c42a;
            transform: translateY(-2px);
        }

        .form-group button:active {
            transform: translateY(0);
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .register-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #218838;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="branding">
            <img src="images/OCTlogo.png" alt="Company Logo" class="logo">
            <h2>Library Management System</h2> 
        </div>

        <div class="login-container">
            <h2>Welcome, Admin!</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="email_acc" name="email_acc" placeholder="Enter your username" required autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="email_password" name="email_password" placeholder="Enter your password" required autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                        <label class="show-password">
                            <input type="checkbox" id="showPassword"> Show
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
                <p>Don't have an account? <a href="register.php">Register now</a></p> 
            </form>
            <div class="register-link"></div>
        </div>
    </div>

    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            let passwordField = document.getElementById('email_password');
            passwordField.type = this.checked ? 'text' : 'password';
        });

        function validateForm(event) {
            event.preventDefault(); 

            if (document.getElementById("emailError")) {
                document.getElementById("emailError").textContent = '';
            }
            if (document.getElementById("passwordError")) {
                document.getElementById("passwordError").textContent = '';
            }

            var email = document.getElementById('email_acc').value;
            var password = document.getElementById('email_password').value;
            var valid = true;

            if (email.trim() === '') {
                if (document.getElementById('emailError')) {
                    document.getElementById('emailError').textContent = 'Email is required.';
                }
                valid = false;
            }

            if (password.trim() === '') {
                if (document.getElementById('passwordError')) {
                    document.getElementById('passwordError').textContent = 'Password is required.';
                }
                valid = false;
            }

            if (valid) {
                console.log("Form is valid. Sending login request...");
                loginRequest(email, password);
            }
        }

        function loginRequest(email, password) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/login.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            var data = "email_acc=" + encodeURIComponent(email) + "&email_password=" + encodeURIComponent(password);
            console.log("Sending data:", data);

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText.trim();
                    console.log("Server response:", response);

                    if (response === "success") {
                        console.log("Login successful. Redirecting to homepage.php...");
                        window.location.href = "homepage.php"; 
                    } else {
                        alert("Invalid credentials! Please try again.");
                    }
                }
            };

            xhr.send(data);
        }

        document.getElementById('loginForm').addEventListener('submit', validateForm);
    </script>
</body>
</html>
