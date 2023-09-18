<?php
session_start();
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "lab3";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {  // Pretty much same as demo in class
    $newUsername = $_POST["newUsername"];
    $newPassword = $_POST["newPassword"];

    $selectQuery = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("s", $newUsername);
    $stmt->execute();
    $res = $stmt->get_result();
    $numResults = mysqli_num_rows($res);

    if ($numResults > 0) {
        echo("Username taken: " . $newUsername);
    } else {
        $insertQuery = "INSERT INTO users (username, password, count) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $newUsername, $newPassword);
        $stmt->execute();
        echo "New account added successfully";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h1>Register</h1>
    <form method="post">
        <input type="text" name="newUsername" placeholder="Username" required><br>
        <input type="password" name="newPassword" placeholder="Password" required><br>
        <input type="submit" name="register" value="Register">
    </form>
    <form method="post" action="login.php">
        <input type="submit" name="gotoLogin" value="Go to log in screen">
    </form>

</body>
</html>