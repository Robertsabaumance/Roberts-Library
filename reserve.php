<?php
    session_start();

    $page = 'reserve';

    require_once "connection.php";

    include "header.php";

    if (!isset($_SESSION['username'])) 
    {
        header("Location: login.php");
        exit();
    }

    //get search values from URL, or empty if not there
    $title = $_GET['title'] ?? '';
    $author = $_GET['author'] ?? '';
    $category = $_GET['category'] ?? '';

    //Figure out which page number to show, default is 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    if ($page < 1) $page = 1; //lowest page is 1

    $limit = 5; //show 5 books per page

    //work out where to start showing books for this page
    $pageStart = ($page - 1) * $limit;

    $sql = "SELECT b.ISBN, b.BookTitle, b.Author, b.Reserved, c.CategoryDepartment,
                r.Username, r.ReservedDate
            FROM books b
            JOIN categories c ON b.CategoryID = c.CategoryID
            LEFT JOIN reservations r ON b.ISBN = r.ISBN
            WHERE 1=1"; // makes it easy to add filters later

    // if a title was typed, add it to search
    if (!empty($title)) 
    {
        $sql .= " AND b.BookTitle LIKE '%" . $conn->real_escape_string($title) . "%'";
    }

    // if author typed, add it to search
    if (!empty($author)) 
    {
        $sql .= " AND b.Author LIKE '%" . $conn->real_escape_string($author) . "%'";
    }

    // if category chosen, add it to search
    if (!empty($category)) 
    {
        $sql .= " AND b.CategoryID = " . intval($category);
    }

    //make a copy of query that only counts how many books match
    $countSql = str_replace("SELECT b.ISBN, b.BookTitle, b.Author, b.Reserved, c.CategoryDepartment,
                r.Username, r.ReservedDate", "SELECT COUNT(*) as total", $sql);

    //run the count query
    $countResult = $conn->query($countSql);

    //get number of matching books
    $totalRows = $countResult->fetch_assoc()['total'];

    //work out how many pages are needed
    $totalPages = ceil($totalRows / $limit);

    //On page 2 skip first 5 books, page 3 skip first 10, etc
    $sql .= " ORDER BY b.ISBN LIMIT $limit OFFSET $pageStart";
    $results = $conn->query($sql);

    //feedback messages (success or error) stored here
    $message = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve'])) 
    {
        $isbn = $_POST['reserve'];
        $username = $_SESSION['username'];
        $date = date('Y-m-d'); 

        //Check if book already reserved
        $check = $conn->query("SELECT Reserved FROM books WHERE ISBN = '$isbn'");
        if ($check && $check->num_rows > 0) 
        {
            $status = $check->fetch_assoc()['Reserved'];
            if ($status === 'N') 
            {
                // mark book reserved + save reservation
                $conn->query("UPDATE books SET Reserved = 'Y' WHERE ISBN = '$isbn'");
                $conn->query("INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES ('$isbn', '$username', '$date')");
                $message[] = "Book reserved successfully!";
            } 
            else 
            {
                $message[] = "Sorry, this book is already reserved.";
            }
        }
    }
?>

<center>
    <br><br><br><br>
    <?php if (!empty($message)): ?>
        <ul class="feedback">
            <?php foreach ($message as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($results && $results->num_rows > 0): ?>
        <form method="POST" action="reserve.php?title=
            <?= urlencode($title) ?>
                &author=<?= urlencode($author) ?>
                &category=<?= urlencode($category) ?>
                &page=<?= $page ?>">

            <!-- shows search results and page controls -->
            <?php include "display_reservations.php"; ?> 
        </form>
    <?php elseif (empty($message)): ?>
        <p>No books found.</p>
    <?php endif; ?>
</center>

<?php include "footer.php"; ?>