<?php include 'db.php';
// delete.php — DELETE (the "D" in CRUD)
// This page has no HTML of its own. It just deletes one student
// and then immediately sends the user back to the list.

// Read which student to remove from the URL   (delete.php?id=5  ->  $id = 5).
$id = $_GET['id'];

// "DELETE FROM students WHERE id=$id" removes ONLY the row with this id.
// WARNING: leaving out the WHERE would delete every student in the table!
$conn->query("DELETE FROM users WHERE id=$id");

// header("Location: ...") redirects the browser back to the list page.
header("Location: index.php");
?>
