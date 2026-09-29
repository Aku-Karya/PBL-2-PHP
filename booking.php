<?php
// All the form handling goes BEFORE header.php, because header("Location: ...")
// only works if nothing has been printed to the page yet.
include 'db.php';

$errors = array();
$name = $email = $phone = $gender = '';
$service_id = (int)($_GET['id'] ?? 0);   // lets the "Book Now" link (booking.php?id=3) preselect a service

if (isset($_POST['save'])) {
  $name       = trim($_POST['name'] ?? '');
  $email      = trim($_POST['email'] ?? '');
  $phone      = trim($_POST['phone_number'] ?? '');
  $gender     = $_POST['gender'] ?? '';
  $service_id = (int)($_POST['service_id'] ?? 0);

  // --- Validate ---
  if ($name === '') $errors['name'] = 'A name is required.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
  if (!preg_match('/^[0-9+ -]{9,15}$/', $phone)) $errors['phone'] = 'Enter a valid phone number.';
  if (!in_array($gender, array('Male', 'Female', 'Others'), true)) $errors['gender'] = 'Choose a gender.';

  // Look the service up in the database so we never trust the browser's value.
  $service = null;
  if ($service_id > 0) {
    $stmt = $conn->prepare("SELECT id, name FROM services WHERE id = ?");
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $service = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }
  if (!$service) $errors['service'] = 'Choose a service from the list.';

  // --- Save ---
  if (!$errors) {
    try {
      // A transaction means BOTH changes are saved, or NEITHER is.
      $conn->begin_transaction();

      // 1) Add the patient. No id here: the database numbers it automatically.
      $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, gender, services) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $name, $email, $phone, $gender, $service['name']);
      $stmt->execute();
      $new_id = $conn->insert_id;
      $stmt->close();

      // 2) Add the patient's name to that service's JSON list in services.user.
      //    NULL becomes [] first, then the name is appended: ["Amina", "Aiman"]
      $stmt = $conn->prepare("UPDATE services SET user = JSON_ARRAY_APPEND(COALESCE(user, JSON_ARRAY()), '\$', ?) WHERE id = ?");
      $stmt->bind_param('si', $name, $service['id']);
      $stmt->execute();
      $stmt->close();

      $conn->commit();
      header("Location: booking.php?booked=" . $new_id);
      exit;
    } catch (Throwable $e) {
      $conn->rollback();
      $errors['form'] = 'Sorry, the booking could not be saved. Please try again.';
    }
  }
}

$booked   = (int)($_GET['booked'] ?? 0);
$services = $conn->query("SELECT id, name, fee FROM services ORDER BY name");
?>
<?php include 'header.php'; ?>

<h2>Appointment Booking</h2>

<?php if ($booked): ?>
  <p><strong>Booking confirmed.</strong> Your patient ID is <strong><?php echo $booked; ?></strong>. Keep it, because you will need it to log in.</p>
<?php endif; ?>

<?php if (isset($errors['form'])): ?>
  <p><?php echo htmlspecialchars($errors['form']); ?></p>
<?php endif; ?>

<form method="post">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
  <?php if (isset($errors['name'])) echo '<div>' . htmlspecialchars($errors['name']) . '</div>'; ?><br>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
  <?php if (isset($errors['email'])) echo '<div>' . htmlspecialchars($errors['email']) . '</div>'; ?><br>

  <label for="phone_number">Phone Number:</label>
  <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>">
  <?php if (isset($errors['phone'])) echo '<div>' . htmlspecialchars($errors['phone']) . '</div>'; ?><br>

  <label for="gender">Gender:</label>
  <select id="gender" name="gender">
    <option value="">Select gender</option>
    <?php foreach (array('Male', 'Female', 'Others') as $g): ?>
      <option value="<?php echo $g; ?>" <?php if ($gender === $g) echo 'selected'; ?>><?php echo $g; ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['gender'])) echo '<div>' . htmlspecialchars($errors['gender']) . '</div>'; ?><br>

  <label for="service_id">Service:</label>
  <select id="service_id" name="service_id">
    <option value="">Select a service</option>
    <?php while ($s = $services->fetch_assoc()): ?>
      <option value="<?php echo $s['id']; ?>" <?php if ($service_id === (int)$s['id']) echo 'selected'; ?>>
        <?php echo htmlspecialchars($s['name']); ?> (RM<?php echo (int)$s['fee']; ?>)
      </option>
    <?php endwhile; ?>
  </select>
  <?php if (isset($errors['service'])) echo '<div>' . htmlspecialchars($errors['service']) . '</div>'; ?><br>

  <input type="submit" name="save" value="Book appointment">
</form>

<?php include 'footer.php'; ?>