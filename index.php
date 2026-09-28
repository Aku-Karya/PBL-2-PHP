<?php include 'db.php'; ?>
<!--
  index.php — READ (the "R" in CRUD)
  Lists every student from the database in a table.
  The line above runs db.php first, so $conn (our database
  connection) already exists and is ready to use here.
-->
<h2>Student List</h2>

<!-- A link (the <a> "anchor" tag). Clicking it opens the add-student page. -->
<a href="add.php">Add New Student</a>

<!-- Start an HTML table. border="1" draws the grid lines; cellpadding adds spacing inside each cell. -->
<table border="1" cellpadding="10">
<tr>
  <!-- <tr> = table row.  <th> = a bold header cell (table heading). -->
  <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Action</th>
</tr>
<?php
// $conn->query(...) sends an SQL command to the database and returns the result.
// "SELECT * FROM students" means: fetch ALL columns (*) of every row in the "students" table.
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
      <a href='edit.php?id=".$row['id']."'>Edit</a> |
      <a href='delete.php?id=".$row['id']."'>Delete</a>
    </td>
  </tr>";
  // Note: edit.php?id=...  and  delete.php?id=...  put the student's id into the
  // URL, so the next page knows exactly which student to edit or delete.
}
?>
</table>