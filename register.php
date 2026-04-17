<?php

// Include the database connection so we can interact with the users table
require "includes/connect.php";

// Array to store validation errors
$errors = [];

// Variable to store a success message if the account is created
$success = "";

// Check if the form was submitted using POST
// This ensures the registration logic only runs when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize the username from the form
    // filter_input helps clean user input
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));

    // Retrieve and sanitize the email address
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

    // Retrieve password fields (no sanitizing because passwords may contain special characters)
    $password = $_POST['password'] ?? '';

    // -----------------------------
    // Server-side Validation
    // -----------------------------

    // Check that a username was entered
    if ($username === '') {
        $errors[] = "Username is required.";
    }

    // Check that an email was entered
    if ($email === '') {
        $errors[] = "Email is required.";
    }
    // Validate the email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email must be a valid email address.";
    }

    // Check that a password was entered
    if ($password === '') {
        $errors[] = "Password is required.";
    }
   

    // Enforce a minimum password length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // --------------------------------------------------
    // Check if the username or email already exists
    // --------------------------------------------------

    // Only check the database if there are no validation errors so far
    if (empty($errors)) {

        // SQL query to check for existing username or email
        $sql = "SELECT id FROM users1 WHERE username = :username OR email = :email";

        // Prepare the SQL statement using PDO
        $stmt = $pdo->prepare($sql);

        // Bind user inputs to the query parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        // Execute the query
        $stmt->execute();

        // If a record is returned, the username or email is already in use
        if ($stmt->fetch()) {
            $errors[] = "That username or email is already in use.";
        }
    }

    // --------------------------------------------------
    // Insert the new user into the database
    // --------------------------------------------------

    // Only insert if there are still no errors
    if (empty($errors)) {

        // Hash the password before storing it in the database
        // This ensures passwords are not stored in plain text
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users1 (username, email, password)
                VALUES (:username, :email, :password)";

        // Prepare the insert statement
        $stmt = $pdo->prepare($sql);

        // Bind the values to the query parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the insert query
        $stmt->execute();

        // Set a success message
        $success = "Account created successfully. You can now log in.";
    }
}
?>

<main>


    <h2>Register for an account here:</h2>
    <form>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>
</main>