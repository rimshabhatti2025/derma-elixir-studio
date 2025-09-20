<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Validate input (optional but recommended)
  if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo "<script>alert('All fields are required!'); window.location.href='contact.php';</script>";
    exit();
  }

  // Insert data into the database
  $sql = "INSERT INTO contacts (name, email, subject, message) 
          VALUES ('$name', '$email', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Thank you for contacting us! We will get back to you soon.'); window.location.href='contact.php';</script>";
  } else {
    echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "'); window.location.href='contact.php';</script>";
  }

  // Close the database connection
  $conn->close();
} else {
  // Redirect to the contact page if the form is not submitted
  header("Location: contact.php");
  exit();
}
