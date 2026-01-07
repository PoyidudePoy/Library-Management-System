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
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f9;
            padding: 20px; 
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #45a049;
            outline: none;
        }

        /* Password Wrapper */
        .password-wrapper {
            display: flex;
            align-items: center;
            width: 100%;
            position: relative;
        }

        .password-wrapper input {
            flex-grow: 1;
        }

        .password-wrapper label {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-left: 10px;
            cursor: pointer;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #45a049;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .form-group button:hover {
            background-color: #3e8e41;
        }

        .form-container p {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container p a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .form-container {
                padding: 20px;
                max-width: 90%;
            }

            .form-group input,
            .form-group select,
            .form-group button {
                font-size: 14px;
                padding: 10px;
            }

            .form-container h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                max-width: 100%;
            }

            .form-group input,
            .form-group select,
            .form-group button {
                font-size: 13px;
                padding: 8px;
            }

            .form-container h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Account</h2>
       
        <form method="POST" action="php/register.php">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter your student ID" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="octEmail">OCT Email</label>
                <input type="email" id="octEmail" name="octEmail" placeholder="Enter your OCT email" required>
            </div>
            <div class="form-group">
                <label for="email_password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="email_password" name="email_password" placeholder="Enter your password" required>
                    <label>
                        <input type="checkbox" id="show_password"> Show
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="grade_level">Grade Level</label>
                <select id="grade_level" name="grade_level" required>
                    <option value="">Select Grade Level</option>
                    <option value="11">Grade 11</option>
                    <option value="12">Grade 12</option>
                </select>
            </div>
            <div class="form-group">
                <label for="strand">Strand</label>
                <select id="strand" name="strand" required>
                    <option value="">Select Strand</option>
                    <option value="STEM">STEM</option>
                    <option value="ABM">ABM</option>
                    <option value="HUMSS">HUMSS</option>
                    <option value="TVL-ICT">TVL-ICT</option>
                    <option value="TVL-HE">TVL-H.E</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Create Account</button>
            </div>
            <p>Have an account? <a href="loginNOW.php">Login now</a></p> 
        </form>
    </div>

    <script>
        // Show/Hide Password Toggle
        document.getElementById('show_password').addEventListener('change', function () {
            let passwordField = document.getElementById('email_password');
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>

</body>
</html>
