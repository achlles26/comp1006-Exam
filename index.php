<?php

// start session
    session_start();

    // include connect
    require "includes/connect.php";

    // error variable
    $error = "";

    // check request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // set  variables
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // check if info is present
        if ($username === '' || $password === '' || $email === '') {

            $error = "Username/email and password are required.";
        } else {

        // sql statement
            $sql = "SELECT id, username, email, password
                    FROM users1
                    WHERE username = :login
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':login', $username);
            $stmt->execute();   // execute

            // fetch info
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // check if password verifies
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: manager.php");
                exit;
            } else {
                $error = "Invalid credentials. Please try again.";
            }
        }
}
?>
<main>

    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Log In</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</main>