<?php
  // Get the form data
  $name = $_POST["name"];
  $email = $_POST["email"];

  // Insert the data into the database
  $sql = "UPDATE users SET name='$name', email='$email' WHERE username='$username'";
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
?>
