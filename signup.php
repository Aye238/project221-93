<?php
include '../config/database.php';

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["new-username"];
    $email = $_POST["new-email"];
    $password = $_POST["new-password"];
    $confirm_password = $_POST["confirm-password"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // --- REMOVE PASSWORD HASHING ---
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);  <-- REMOVE THIS LINE

        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_message = "Username or email already taken.";
        } else {
            // --- STORE PLAIN TEXT PASSWORD (INSECURE) ---
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $password); // Use $password directly

            if ($stmt_insert->execute()) {
                $success_message = "Registration successful! <a href='../public/html/login.html'>Login here</a>";
            } else {
                $error_message = "Error during registration.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <section id="signup-form" class="signup-section">
        <h2>Create an Account</h2>

        <?php if ($success_message): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="signup.php" method="POST" id="signup" class="form-container">
            <label for="new-username">Username</label>
            <input type="text" id="new-username" name="new-username" required placeholder="Choose your username">

            <label for="new-email">Email</label>
            <input type="email" id="new-email" name="new-email" required placeholder="Enter your email">

            <label for="new-password">Password</label>
            <input type="password" id="new-password" name="new-password" required placeholder="Choose a password">

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirm your password">

            <button type="submit" class="btn-submit">Sign Up</button>
        </form>
        <p class="signup-help">Already have an account? <a href="../public/html/login.html">Login</a></p>
    </section>
</body>
</html>