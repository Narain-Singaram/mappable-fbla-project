<?php
$name = $_POST['name'];
$email = $_POST['email'];


$sql = "INSERT INTO notifications (name, email) VALUES ('$name', '$email')";

if ($connectionn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $connectionn->error;
}
?>
