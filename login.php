<?php
session_start();
include('db.php');

$errors = array('name' => '', 'email' => '', 'id' => '', 'login' => '');
$name = $email = $id = '';

if (isset($_POST['submit'])) {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id    = trim($_POST['id'] ?? '');

    if ($name === '') {
        $errors['name'] = 'A name is required';
    } elseif (!preg_match("/^[\p{L}\s.'\/@-]+$/u", $name)) {
        $errors['name'] = 'Name can only contain letters, spaces and . \' / @ -';
    }

    if ($email === '') {
        $errors['email'] = 'An email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid email address';
    }

    // ID: digits only (the original pattern allowed LETTERS only, the opposite of the message)
    if ($id === '') {
        $errors['id'] = 'An ID is required';
    } elseif (!ctype_digit($id)) {
        $errors['id'] = 'ID must be numbers only';
    }

    // Only touch the database if every field passed validation.
    if (!array_filter($errors)) {
        $stmt = $conn->prepare("SELECT id, name FROM users WHERE id = ? AND email = ? AND name = ?");
        $stmt->bind_param('sss', $id, $email, $name);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: index.php');
            exit;
        }
        $errors['login'] = 'You are not a registered patient';
    }
}
?>
<!DOCTYPE html>
<html>
    <body>
    <?php include('header.php'); ?>

<section>
    <h4>Log In</h4>
    <form action="login.php" method="POST">

    <div><?php echo htmlspecialchars($errors['login']); ?></div>

    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
    <div><?php echo htmlspecialchars($errors['name']); ?></div>

    <label for="email">Your Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <div><?php echo htmlspecialchars($errors['email']); ?></div>

    <label for="id">Your ID:</label>
    <input type="text" id="id" name="id" inputmode="numeric" value="<?php echo htmlspecialchars($id); ?>">
    <div><?php echo htmlspecialchars($errors['id']); ?></div>

    <div>
        <input type="submit" name="submit" value="Log in" class="btn brand z-depth-0">
    </div>

    </form>
</section>

    <?php include('footer.php'); ?>
    </body>
</html>