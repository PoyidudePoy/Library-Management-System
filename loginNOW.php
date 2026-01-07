<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email & Password Login</title>

    <script>
        sessionStorage.removeItem('isLoggedIn');
    </script>

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
            background: linear-gradient(135deg, #62e230de, #17a117b0);
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            flex-direction: column;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            width: 100%;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.3);
        }

        /* Password Field with Show Password */
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

        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #218b00f3;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }

        .form-group button:hover {
            background: #23cc01;
            transform: translateY(-2px);
        }

        .form-group button:active {
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .login-container {
                max-width: 100%;
                padding: 20px;
            }

            .password-wrapper {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="octEmail">OCT Email:</label>
                <input type="email" id="octEmail" name="octEmail" placeholder="Enter your OCT email" required>
            </div>
            <div class="form-group">
                <label for="email_password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="email_password" name="email_password" placeholder="Enter your password" required>
                    <label>
                        <input type="checkbox" id="show_password"> Show
                    </label>
                </div>
            </div>
            <div class="form-group">    
                <button type="submit">Login</button>
            </div>
            <p>Don't have an account? <a href="createacc.php">Register now</a></p>
            <p id="message" class="message"></p>
        </form>
    </div>

    <script>
      // Show/Hide Password Toggle
      document.getElementById('show_password').addEventListener('change', function () {
          let passwordField = document.getElementById('email_password');
          passwordField.type = this.checked ? 'text' : 'password';
      });

      // Login Form Submission
      document.getElementById('loginForm').addEventListener('submit', function(event) {
          event.preventDefault();  

          let octEmail = document.getElementById('octEmail').value.trim();
          let password = document.getElementById('email_password').value.trim();
          const messageElement = document.getElementById('message');

          if (octEmail && password) {
              fetch('php/login.php', {  
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ octEmail: octEmail, password: password })  
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      messageElement.style.color = 'green';
                      messageElement.textContent = 'Login successful! Redirecting...';
                      sessionStorage.setItem('isLoggedIn', 'true'); 
                      setTimeout(() => {
                          window.location.href = 'homepagefinall.php';  
                      }, 1500);
                  } else {
                      messageElement.style.color = 'red';
                      messageElement.textContent = data.message;  
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  messageElement.style.color = 'red';
                  messageElement.textContent = 'An error occurred. Please try again.';  
              });
          } else {
              messageElement.style.color = 'red';
              messageElement.textContent = 'Please fill in all fields.';  
          }
      });
    </script>

</body>
</html>
