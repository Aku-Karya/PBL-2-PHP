<?php include 'db.php'; ?>
<?php include 'header.php'; ?>

<?php
$result = $conn->query("SELECT * FROM services");
 
while($row = $result->fetch_assoc()) {

  echo "<div style='border: 2px solid #333; padding: 10px; margin-bottom: 10px;'>
  Service Name: ".$row['name']." <br>
  Service Fee: RM".$row['fee']."<br>Description:".$row['description']."<br>
  <a href='booking.php?id=".$row['id']."'>Book Now</a><br></div>";
}
?>
<?php include 'footer.php'; ?>