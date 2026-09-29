<?php include 'db.php'; ?>
<?php include 'header.php'; ?>

<h2>Patience Data</h2>

<!-- A link (the <a> "anchor" tag). Clicking it opens the add-student page. -->
<a href="add.php">Add New Patient</a>

<!-- Start an HTML table. border="1" draws the grid lines; cellpadding adds spacing inside each cell. -->
<table border="1" cellpadding="10">
<tr>
  <!-- <tr> = table row.  <th> = a bold header cell (table heading). -->
  <th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th>Gender</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM users");
// A "while" loop repeats its block once for each row that comes back.
// $result->fetch_assoc() returns the NEXT row as an "associative array"
// (an array whose values are read by column name, e.g. $row['name']).
// When there are no rows left it returns null, which ends the loop.
while($row = $result->fetch_assoc()) {
  // echo prints HTML to the page. The dots ( . ) glue the text and the variables together.
  // <td> = a normal table cell (table data).
  echo "<tr>
    <td>".$row['id']."</td>
    <td>".$row['name']."</td>
    <td>".$row['email']."</td>
    <td>".$row['phone_number']."</td>
    <td>".$row['gender']."</td>
    <td>
      <a href='edit_user.php?id=".$row['id']."'>Edit</a> |
      <a href='delete_user.php?id=".$row['id']."'>Delete</a>
    </td>
  </tr>";
  // Note: edit.php?id=...  and  delete.php?id=...  put the student's id into the
  // URL, so the next page knows exactly which student to edit or delete.
}
?>
</table>

<h2>Services Data</h2>

<!-- A link (the <a> "anchor" tag). Clicking it opens the add-student page. -->
<a href="add.php">Add New Service</a>

<!-- Start an HTML table. border="1" draws the grid lines; cellpadding adds spacing inside each cell. -->
<table border="1" cellpadding="10">
<tr>
  <!-- <tr> = table row.  <th> = a bold header cell (table heading). -->
  <th>ID</th><th>Name</th><th>Fee</th><th>Description</th><th>User</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM services");
 
while($row = $result->fetch_assoc()) {
 
  echo "<tr>
    <td>".$row['id']."</td>
    <td>".$row['name']."</td>
    <td>".$row['fee']."</td>
    <td>".$row['description']."</td>
    <td>".$row['user']."</td>
    <td>
      <a href='edit_service.php?id=".$row['id']."'>Edit</a> |
      <a href='delete_service.php?id=".$row['id']."'>Delete</a>
    </td>
  </tr>";
}
?>
</table>

<?php include 'footer.php'; ?>
