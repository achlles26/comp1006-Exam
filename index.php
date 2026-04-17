<?php

    session_start();

    require "includes/connect.php";
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
</main>