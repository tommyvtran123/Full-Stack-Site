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

if (!isset($_SESSION["userid"])) { // checks if a user session already in progress. It not redirects to login
    header("Location: login.php");
    exit();
}

$userid = $_SESSION["userid"]; // identifies logged in user
$username = "";  // variables to be assigned with user information
$count = 0; 

$selectQuery = "SELECT username, count FROM users WHERE userid = ?";
$stmt = $conn->prepare($selectQuery);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) { // if row is fetches user information stored into variables
    $username = $row["username"];
    $count = $row["count"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["increment"])) { // checks for post and user click on the increment button
    $newCount = $count + 1; // increments count
    $updateQuery = "UPDATE users SET count = ? WHERE userid = ?"; 
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $newCount, $userid);
    $stmt->execute();
    header("Location: main.php");
    exit();
}

$conn->close();
?>

<h1>Counter</h1>
    <p>User: <?php echo $username; ?></p>
    <p><?php echo $username; ?>'s count: <?php echo $count; ?></p>
    
    <form method="post">
        <input type="submit" name="increment" value="Increment">
    </form>

    <form method="post" action="login.php">
        <input type="submit" name="gotoLogin" value="Log out and go to log in screen">
    </form>

    <form method="post" action="register.php">
        <input type="submit" name="gotoRegister" value="Log out and go to registration form">
    </form>
</body>
</html>