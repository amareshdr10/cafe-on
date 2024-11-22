<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}
$email = $_SESSION['email'];

// Fetch current user data from the database
$conn = new mysqli("localhost:3307", "root", "", "cafeon_register");
$sql = "SELECT firstname, lastname, password FROM register_details WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $password);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
</head>
<body>
    <form action="process-update-profile.php" method="post">
        <input type="text" name="firstname" value="<?php echo $firstname; ?>" required>
        <input type="text" name="lastname" value="<?php echo $lastname; ?>" required>
        <input type="password" name="password" value="<?php echo $password; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
