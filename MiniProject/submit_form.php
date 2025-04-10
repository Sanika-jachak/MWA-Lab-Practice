<?php
$servername = "localhost:3307"; // Change to "localhost:3307" if your MySQL runs on port 3307
$username = "root";
$password = "";
$database = "clubentry_db";

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $database);
    $conn->set_charset("utf8mb4");
    echo "Database Connected!";

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        print_r($_POST);  // Debugging: Print received form data
        echo "<br>"; 
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $event = trim($_POST['event'] ?? '');

        // Validate input fields
        if (empty($name) || empty($email) || empty($phone) || empty($event)) {
            die("Error: All fields are required!");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Error: Invalid email format!");
        }

        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            die("Error: Invalid phone number!");
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO registrations (name, email, phone, event) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $event);

        if ($stmt->execute()) {
            echo "Data inserted successfully!";
            header("Location: thankyou.html");
            exit();
        } else {
            die("Error inserting data: " . $stmt->error);
        }
    } else {
        die("Error: Form not submitted correctly.");
    }
} catch (mysqli_sql_exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>