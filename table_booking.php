<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize input
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $phone = trim(htmlspecialchars($_POST['phone']));
    $date = trim(htmlspecialchars($_POST['date']));
    $time = trim(htmlspecialchars($_POST['time']));
    $guests = intval($_POST['guests']);

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='container mt-5 alert alert-danger'>Invalid email format.</div>";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        echo "<div class='container mt-5 alert alert-danger'>Invalid phone number.</div>";
    } elseif ($guests <= 0) {
        echo "<div class='container mt-5 alert alert-danger'>Number of guests must be at least 1.</div>";
    } else {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, date, time, guests) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $email, $phone, $date, $time, $guests);

        if ($stmt->execute()) {
            echo "<div class='container mt-5 alert alert-success'>";
            echo "<strong>Thank you, $name!</strong> Your table has been booked for $date at $time for $guests guest(s). Confirmation will be sent to $email.";
            echo "</div>";
        } else {
            echo "<div class='container mt-5 alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
