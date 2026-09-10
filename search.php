<?php
    session_start();

    $page = 'search'; 

    require_once "connection.php";

    include "header.php";
    
    if (!isset($_SESSION['username'])) 
    {
        header("Location: login.php");
        exit();
    }

    $categories = $conn->query("SELECT CategoryID, CategoryDepartment FROM categories");

?>

    <center>
        <br><br><br>

        <!--form sends search inputs to reserve.php-->
        <form method="GET" action="reserve.php">
            <p>
                <label for="inputTitle">Title:</label>
                <br><input type="text" name="title" id="inputTitle" size="25" autocomplete="off">
            </p>
            <p>
                <label for="inputAuthor">Author:</label>
                <br><input type="text" name="author" id="inputAuthor" size="25" autocomplete="off">
            </p>
            <p>
                <label for="inputCategory">Category:</label>
                <br><select name="category" id="inputCategory"> <!--dropdown (select)-->
                    <option value="">-- Select Category --</option> <!--empty by default (value=""), doesnt crash if category isnt chosen-->

                    <?php while($row = $categories->fetch_assoc()): ?> <!--loop thorugh each category row in db-->
                        <option value="<?= $row['CategoryID'] ?>"><!--find category using ID-->
                            <?= $row['CategoryDepartment'] ?>
                        </option>
                    <?php endwhile; ?>

                </select>
            </p>
            <p>
                <input type="submit" value="Search">
            </p>
        </form>
    </center>

<?php include "footer.php"; ?>