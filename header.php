<?php
    //start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($page)) 
    {
        $currentFile = basename($_SERVER['PHP_SELF']);
        switch ($currentFile) {
            case 'login.php':
                $page = 'login';
                break;
            case 'registration.php':
                $page = 'registration';
                break;
            case 'homepage.php':
                $page = 'homepage';
                break;
            case 'search.php':
                $page = 'search';
                break;
            case 'reserve.php':
                $page = 'reserve';
                break;
            case 'myReservations.php':
                $page = 'myReservations';
                break;
            default:
                $page = 'other';
                break;
        }
    }

    // Check if the user is logged in
    $isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="library.css">
    <title>Robert's Library</title>
    <style>
        .header-bar {
            background-color: #B3B3B3;
            padding: 10px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .header-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .header-buttons form {
            display: inline;
        }
    </style>
</head>
<body>

<div class="header-bar">
    <center>
        <h1>Robert's Library</h1>
        <?php
        switch ($page) 
        {
            case 'login':
                echo '<h2>Login Page</h2>';
                break;
            case 'registration':
                echo '<h2>Registration Page</h2>';
                break;
            case 'homepage':
                echo '<h2>Home Page</h2>';
                break;
            case 'search':
                echo '<h2>Search Page</h2>';
                break;
            case 'reserve':
                echo '<h2>Reserve Page</h2>';
                break;
            case 'myReservations':
                echo '<h2>My Reservations</h2>';
                break;
            default:
                echo '<h2>Welcome</h2>';
                break;
        }
        ?>
    </center>
</div>

<?php
// Show header buttons only if user is logged in and not login/registration
if ($isLoggedIn && $page !== 'login' && $page !== 'registration'):
?>
<div class="header-buttons">
    <?php
    // Custom buttons per page
    switch ($page) {
        case 'homepage':
            echo '<form method="GET" action="reserve.php"><input type="submit" value="Reserve"></form>';
            echo '<form method="GET" action="search.php"><input type="submit" value="Search"></form>';
            echo '<form method="GET" action="myReservations.php"><input type="submit" value="My Reservations"></form>';
            echo '<form method="POST" action="logout.php" onsubmit="return confirm(\'Are you sure you want to log out?\');"><input type="submit" value="Logout"></form>';
            break;
        case 'search':
            echo '<form method="GET" action="homepage.php"><input type="submit" value="Home"></form>';
            echo '<form method="GET" action="reserve.php"><input type="submit" value="Reserve"></form>';
            echo '<form method="GET" action="myReservations.php"><input type="submit" value="My Reservations"></form>';
            echo '<form method="POST" action="logout.php" onsubmit="return confirm(\'Are you sure you want to log out?\');"><input type="submit" value="Logout"></form>';
            break;
        case 'reserve':
            echo '<form method="GET" action="homepage.php"><input type="submit" value="Home"></form>';
            echo '<form method="GET" action="search.php"><input type="submit" value="Search"></form>';
            echo '<form method="GET" action="myReservations.php"><input type="submit" value="My Reservations"></form>';
            echo '<form method="POST" action="logout.php" onsubmit="return confirm(\'Are you sure you want to log out?\');"><input type="submit" value="Logout"></form>';
            break;
        case 'myReservations':
            echo '<form method="GET" action="homepage.php"><input type="submit" value="Home"></form>';
            echo '<form method="GET" action="search.php"><input type="submit" value="Search"></form>';
            echo '<form method="GET" action="reserve.php"><input type="submit" value="Reserve"></form>';
            echo '<form method="POST" action="logout.php" onsubmit="return confirm(\'Are you sure you want to log out?\');"><input type="submit" value="Logout"></form>';
            break;
        default:
            // Default: just logout
            echo '<form method="POST" action="logout.php" onsubmit="return confirm(\'Are you sure you want to log out?\');"><input type="submit" value="Logout"></form>';
            break;
    }
    ?>
</div>
<?php endif; ?>
