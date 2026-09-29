<?php include 'db.php';
 include 'header.php'; ?>

<?php
$id = $_GET['id'];

$result = $conn->query("SELECT * FROM services WHERE id=$id");

$row = $result->fetch_assoc();
?>
<h2>Edit Services</h2>

<form method="post">
  Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
  ID: <input type="text" name="id" value="<?php echo $row['id']; ?>"><br>
  Fee: <input type="text" name="fee" value="<?php echo $row['fee']; ?>"><br>
  Description: <input type="text" name="description" value="<?php echo $row['description']; ?>"><br>
  User: <input type="text" name="user" value="<?php echo $row['user']; ?>"><br>
  <input type="submit" name="update" value="Update">
</form>

<?php
// IF the Update button was clicked (its name is "update")...
if(isset($_POST['update'])){
  // ...read the new values the user typed.
  $name   = $_POST['name'];
  $id      = $_POST['id'];
  $email  = $_POST['fee'];
  $phone_number = $_POST['description'];
  $gender = $_POST['user'];

  // UPDATE ... SET ... WHERE id=$id  changes the existing row — only the one with this id.
  // WARNING: without the WHERE, it would overwrite EVERY student, so the WHERE matters a lot!
  $conn->query("UPDATE services SET name='$name',id='$id', fee='$fee', description='$description', user='$user' WHERE id=$id");

  // Redirect back to the list to see the updated student.
  header("Location: index.php");
}
?>

<?php include 'footer.php'; ?>