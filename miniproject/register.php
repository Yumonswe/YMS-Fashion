<?php
session_start();
include 'db.php';
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$name = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
if (!empty($name) && !empty($email) && !empty($password)) {
$check_stmt =$conn->prepare("SELECT id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();$check_stmt->store_result();
if ($check_stmt->num_rows > 0) {$error_message = "This email is already registered.";
} else {
$stmt =$conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->bind_param("sss", $name, $email,$password);
if ($stmt->execute()) {
header("Location: login1.php");
exit();
} else {
$error_message = "Registration failed. Please try again.";
}
$stmt->close();
}
$check_stmt->close();
} else {
$error_message = "Please fill in all fields.";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>
<style>
body {
font-family: Arial, sans-serif;
background-color: #f4f4f4;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
margin: 0;
}
.register-container {
background: white;
padding: 30px;
border-radius: 8px;
box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
width: 350px;
}
h1 {
text-align: center;
color: #333;
font-size: 24px;
margin-bottom: 20px;
}
.form-group {
margin-bottom: 15px;
}
label {
display: block;
margin-bottom: 5px;
color: #555;
font-weight: bold;
}
input[type="text"],
input[type="email"],
input[type="password"] {
width: 100%;
padding: 8px;
box-sizing: border-box;
border: 1px solid #ccc;
border-radius: 4px;
}
input[type="submit"] {
width: 100%;
padding: 10px;
background: #e84757;
color: white;
border: none;
border-radius: 4px;
cursor: pointer;
font-size: 16px;
margin-top: 10px;
}
input[type="submit"]:hover {
background: #d43f4d;
}
.error {
color: red;
font-size: 14px;
margin-bottom: 15px;
text-align: center;
}
#message {
font-size: 14px;
margin-top: 10px;
text-align: center;
}
</style>
</head>
<body>
<div class="register-container">
<h1>Create Account</h1>
<?php if (!empty($error_message)): ?>
<div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>
<form id="registrationForm" action="register.php" method="POST">
<div class="form-group">
<label for="username">Name:</label>
<input type="text" id="username" name="username" required>
</div>
<div class="form-group">
<label for="email">Email:</label>
<input type="email" id="email" name="email" required>
</div>
<div class="form-group">
<label for="password">Password:</label>
<input type="password" id="password" name="password" required>
</div>
<div class="form-group">
<label for="confirmPassword">Confirm Password:</label>
<input type="password" id="confirmPassword" name="confirmPassword" required>
</div>
<input type="submit" value="Register">
<p id="message" role="alert"></p>
</form>
</div>
<script>
const form = document.getElementById("registrationForm");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const message = document.getElementById("message");
form.addEventListener("submit", function(event) {
if (password.value.length < 6) {
event.preventDefault();
message.textContent = "Password must be at least 6 characters.";
message.style.color = "red";
} else if (password.value !== confirmPassword.value) {
event.preventDefault();
message.textContent = "Passwords do not match.";
message.style.color = "red";
}
});
</script>
</body>
</html>