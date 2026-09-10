<?php
    session_start();

    $page = 'myReservations';

    require_once "connection.php";

    include "header.php";

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];
    $message = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return'])) {
        $isbn = $_POST['return'];

        $conn->query("UPDATE books SET Reserved = 'N' WHERE ISBN = '$isbn'");
        $conn->query("DELETE FROM reservations WHERE ISBN = '$isbn' AND Username = '$username'");

        $message[] = "Book returned successfully.";
    }

    // get all books reserved by this user
    $reservedResults = $conn->query("
        SELECT b.ISBN, b.BookTitle, b.Author, c.CategoryDepartment, r.ReservedDate
        FROM reservations r
        JOIN books b ON r.ISBN = b.ISBN
        JOIN categories c ON b.CategoryID = c.CategoryID
        WHERE r.Username = '$username'
    ");
?>

<center>
    <?php if (!empty($message)) : ?>
        <ul class="feedback">
            <?php foreach ($message as $msg) : ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($reservedResults && $reservedResults->num_rows > 0) : ?>
        <form method="POST" action="myReservations.php">
            <?php include "display_reservations.php"; ?>  
        </form>
    <?php else: ?>
        <p>You have no reserved books.</p>
    <?php endif; ?>
</center>

<?php include "footer.php"; ?>