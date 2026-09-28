<?php
// ============================================================
//  db.php  —  DATABASE CONNECTION
//  This file opens the connection to the MySQL database.
//  Every other page runs `include 'db.php';` at the top so it
//  can reuse this same connection instead of writing it again.
// ============================================================

// --- Connection settings ---
// In PHP, a word starting with $ is a "variable" (a labelled box that stores a value).
$servername = "localhost";       // Where the database server runs. "localhost" = this same computer.
$username   = "root";            // The MySQL user name. "root" is the default administrator user.
$password   = "";       // The MySQL password. Leave "" (empty) if you use XAMPP. This value is only for the DBEAVER setup.
$dbname     = "clinic";   // The name of the database we want to open.

// --- Create the connection ---
// `mysqli` is a class that is built into PHP itself (its name means "MySQL Improved").
// You do NOT install it separately — it ships with PHP.
// `new mysqli(...)` uses that class to create a connection object, which we store in $conn.
// We hand it the 4 settings above so it knows which server and database to connect to.
$conn = new mysqli($servername, $username, $password, $dbname);

// --- Check whether the connection failed ---
// The `->` arrow reads a property from (or calls a function on) an object.
// $conn->connect_error holds an error message if the connection could not be made.
if ($conn->connect_error) {
  // die() immediately stops the whole script and prints the message on screen.
  // The `.` (dot) joins two pieces of text together — this is called "concatenation".
  die("Connection failed: " . $conn->connect_error);
}
?>