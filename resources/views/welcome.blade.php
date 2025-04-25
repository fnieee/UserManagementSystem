<!DOCTYPE html>
<html>
<head>
    <title>User Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .welcome-box {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .login-btn {
            background: #3498db;
            color: white;
        }
        .register-btn {
            background: #2ecc71;
            color: white;
        }
    </style>
</head>
<body>
    <div class="welcome-box">
        <div class="logo">User Management</div>
        <div>
            <a href="/login" class="btn login-btn">Login</a>
            <a href="/register" class="btn register-btn">Register</a>
        </div>
    </div>
</body>
</html>
