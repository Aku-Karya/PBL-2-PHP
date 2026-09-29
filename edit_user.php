<?php include 'db.php';
 include 'header.php'; ?>
// edit.php — UPDATE (the "U" in CRUD)
// Loads one student's current details into a form, then saves the changes.
<?php

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM users WHERE id=$id");

$row = $result->fetch_assoc();
?>
<h2>Edit User</h2>

<form method="post">
  Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
  ID: <input type="text" name="id" value="<?php echo $row['id']; ?>"><br>
  Email: <input type="email" name="email" value="<?php echo $row['email']; ?>"><br>
  Phone Number: <input type="text" name="phone_number" value="<?php echo $row['phone_number']; ?>"><br>
  Gender: <input type="text" name="gender" value="<?php echo $row['gender']; ?>"><br>
  <input type="submit" name="update" value="Update">
</form>

<?php
// IF the Update button was clicked (its name is "update")...
if(isset($_POST['update'])){
  // ...read the new values the user typed.
  $name   = $_POST['name'];
  $id      = $_POST['id'];
  $email  = $_POST['email'];
  $phone_number = $_POST['phone_number'];
  $gender = $_POST['gender'];

  // UPDATE ... SET ... WHERE id=$id  changes the existing row — only the one with this id.
  // WARNING: without the WHERE, it would overwrite EVERY student, so the WHERE matters a lot!
  $conn->query("UPDATE users SET name='$name',id='$id', email='$email', phone_number='$phone_number', gender='$gender' WHERE id=$id");

  // Redirect back to the list to see the updated student.
  header("Location: index.php");
}
?>

<?php include 'footer.php'; ?>