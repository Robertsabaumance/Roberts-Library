<?php
session_start();

$page = 'homepage';

include "header.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>

<center>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p>Use the buttons in the top right to navigate the library.</p>
</center>

<?php include "footer.php"; ?>
