<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Customer Portal</title>
</head>
<body>
<h2>Welcome, {{ $user->name }}!</h2>
<p>Your customer portal account has been created. Use the details below to log in:</p>

<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>

<p>You can log in here: <a href="{{ url('/login') }}">Customer Portal Login</a></p>

<p>For security reasons, please change your password after logging in.</p>

<p>Thank you,<br>Our Team</p>
</body>
</html>
