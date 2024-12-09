<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-image: linear-gradient(to bottom, rgba(30, 144, 255, 0.8), rgba(255, 255, 255, 0.8)), url('assets/image.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        
        .header {
            background-color: #1e90ff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }
        
        .login-options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .login-link {
            display: block;
            padding: 15px 20px;
            background-color: #1e90ff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link:hover {
            background-color: #005cbf;
            transform: scale(1.05);
        }
        
        @media (max-width: 500px) {
            .container {
                width: 95%;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Welcome to Inventory System
        </div>
        <div class="login-options">
            <a href="{{ route('admin.login') }}" class="login-link">Admin Login</a>
            <a href="{{ route('department.login') }}" class="login-link">Department Login</a>
        </div>
    </div>
</body>
</html>