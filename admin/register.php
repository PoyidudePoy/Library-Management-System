<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
            animation: fadeIn 1s ease-in-out;
            background: #f4f4f4;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.8s ease-in-out forwards;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #444;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: 0.3s ease;
        }

        .form-group input:focus {
            border-color: #128b02;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.3);
        }

        /* Password field and Show Password checkbox in the same row */
        .password-container {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            transition: 0.3s ease;
        }

        .password-container:focus-within {
            border-color: #128b02;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.3);
        }

        .password-container input {
            border: none;
            flex: 1;
            outline: none;
        }

        .password-container label {
            font-size: 12px;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #128b02;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .form-group button:hover {
            background: #31d11c;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="form-container">
        <h2>Create Admin Account</h2>
        <form method="POST" action="php/register.php">
          
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

         
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" placeholder="Enter your contact number" required>
            </div>

           
            <div class="form-group">
                <label for="email_acc">Email</label>
                <input type="text" id="email_acc" name="email_acc" placeholder="Enter your email" required>
            </div>

           
            <div class="form-group">
                <label for="passWord">Password</label>
                <div class="password-container">
                    <input type="password" id="email_password" name="email_password" placeholder="Enter your password" required>
                    <label>
                        <input type="checkbox" id="togglePassword">
                        show
                    </label>
                </div>
            </div>

           
            <div class="form-group">
                <button type="submit">Create Account</button>
            </div>

            <p>Have an account? <a href="login.php">Login now</a></p> 
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordField = document.getElementById("email_password");

        togglePassword.addEventListener("change", function() {
           
            if (togglePassword.checked) {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        });
    </script>
</body>
</html>
