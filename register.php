<?php
session_start(); // Start the session

$firstname = $_POST['firstname'];
$lastname  = $_POST['lastname'];
$email     = $_POST['email'];
$password  = $_POST['password'];

if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {
    $host = "localhost:3307"; // Ensure the port is correct
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "cafeon_register"; // Your database name

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error ('. mysqli_connect_errno() .') '. mysqli_connect_error());
    } else {
        $SELECT = "SELECT email FROM `register_details` WHERE email = ? LIMIT 1";
        $INSERT = "INSERT INTO `register_details` (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        // Checking if email is already registered
        if ($rnum == 0) {
            $stmt->close();

            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);
            $stmt->execute();

            // Set session after successful signup
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;

            // Redirect to homepage
            header("Location: index.html");
            exit();
        } else {
            echo "Someone already registered with this email.";
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required!";
    die();
}
?>
