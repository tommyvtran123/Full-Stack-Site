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
// handles form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {  // using post instead of get for security but both work
    $loginUsername = $_POST["loginUsername"];  // username from form
    $loginPassword = $_POST["loginPassword"];  // password from form

    $loginQuery = "SELECT * FROM users WHERE username = ? AND password = ?"; // Select query to check username and password exists in the database
    $stmt = $conn->prepare($loginQuery);
    $stmt->bind_param("ss", $loginUsername, $loginPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) { // of 1 row is returned then valid login
        $row = $result->fetch_assoc();
        session_start();      // stores user id  into a new session data and redirects to main.php
        $_SESSION["userid"] = $row["userid"];
        header("Location: main.php");
        exit();
    } else {
        echo "Login failed";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <input type="text" name="loginUsername" placeholder="Username" required><br>
        <input type="password" name="loginPassword" placeholder="Password" required><br>
        <input type="submit" name="login" value="Login">
    </form>
    <form method="post" action="register.php">
        <input type="submit" name="gotoRegister" value="Registration Page">
    </form>

</body>
</html>










