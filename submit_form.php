<?php
// Database connection settings
$host = 'localhost';
$dbname = 'u874583111_taxidispatchdb';
$username = 'u874583111_taxidispatch';
$password = 'hvr4@7tP';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input data
    $errors = [];
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Simple validation
    if (empty($name) || empty($message)) {
        $errors[] = "Name and Message are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
        exit; // Stop further execution
    }

    // PDO database connection
    try {
        $pdo = new PDO("mysql:host=$host; port = 3306; dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to insert data
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);

        echo "Message sent successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Display error message
    }
} else {
    echo "Invalid request method.";
}
?>
