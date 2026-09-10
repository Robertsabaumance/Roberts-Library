<?php
    //decide if we are on the search page or the myReservations page
    $isSearchPage = isset($results) && $results instanceof mysqli_result;

    //choose the right data set depending on the page
    $bookData = $isSearchPage ? $results : (isset($reservedResults) ? $reservedResults : null);

    //if no data, stop here
    if (!$bookData) {
        echo "<p>Nothing to display.</p>";
        return;
    }
?>

<table border="1" cellpadding="8" cellspacing="0" style="background-color: white;">
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Status</th>
        <th>Reserved Date</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $bookData->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['BookTitle'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['Author'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['CategoryDepartment'] ?? '') ?></td>

            <td>
                <?php if ($isSearchPage): ?>
                    <?php if (($row['Reserved'] ?? 'N') === 'N'): ?>
                        <span style="color:green;">Available</span>
                    <?php else: ?>
                        <span style="color:red;">Already Reserved</span>
                        <?php if (!empty($row['Username'])): ?>
                            <br>by <?= htmlspecialchars($row['Username']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <span style="color:red;">Reserved by you</span>
                <?php endif; ?>
            </td>

            <td>
                <?php if (!empty($row['ReservedDate'])): ?>
                    <?= htmlspecialchars($row['ReservedDate']) ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>

            <td>
                <?php if ($isSearchPage): ?>
                    <?php if (($row['Reserved'] ?? 'N') === 'N'): ?>
                        <button type="submit" name="reserve" value="<?= htmlspecialchars($row['ISBN'] ?? '') ?>">Reserve</button>
                    <?php elseif (!empty($row['Username']) && isset($username) && $row['Username'] === $username): ?>
                        <button type="submit" name="return" value="<?= htmlspecialchars($row['ISBN'] ?? '') ?>">Return Book</button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                <?php else: ?>
                    <button type="submit" name="return" value="<?= htmlspecialchars($row['ISBN'] ?? '') ?>">Return Book</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php if ($isSearchPage && isset($page) && isset($totalPages)): ?>
    <div style="margin-top:20px;">
        <?php if ($page > 1): ?>
    <a href="reserve.php?title=<?= urlencode($title ?? '') ?>&author=<?= urlencode($author ?? '') ?>&category=<?= urlencode($category ?? '') ?>&page=<?= $page - 1 ?>">Previous</a>
<?php endif; ?>

Page <?= (int)$page ?> of <?= (int)$totalPages ?>

<?php if ($page < $totalPages): ?>
    <a href="reserve.php?title=<?= urlencode($title ?? '') ?>&author=<?= urlencode($author ?? '') ?>&category=<?= urlencode($category ?? '') ?>&page=<?= $page + 1 ?>">Next</a>
<?php endif; ?>
    </div>
<?php endif; ?>