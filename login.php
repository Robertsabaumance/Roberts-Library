<?php
    //used to remember who is logged in
    session_start();

    //tell header.php which page we are on
    $page = 'login'; 
        
    require_once "connection.php";

    // Check if the login form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        //get username and password from form, removes extra spaces
        $UserName = trim($_POST['username']);
        $Password = trim($_POST['password']);
        $errors = []; //array to store error messages

        //statement to prevent sql injection
        $stmt = $conn->prepare("SELECT Password FROM users WHERE Username = ?"); // find password for username ?
        $stmt->bind_param("s", $UserName); //replaces ? with username typed in
        $stmt->execute(); // searching in the database for the username
        $stmt->store_result(); // keeps results

        //if a user with this username exists
        if ($stmt->num_rows > 0) // checks if the database returned at least one row
        {
            $stmt->bind_result($storedPassword); // get password stored
            $stmt->fetch(); // put it into $storedPassword

            //if ($Password === $storedPassword)//for testing

            if (password_verify($Password, $storedPassword))
            {
                // store user info in session (saves for all pages)
                $_SESSION['username'] = $UserName;

                header("Location: homepage.php");
                exit();
            } 
            else 
            {
                $errors[] = "Invalid password.";
            }
        } 
        else 
        {
            $errors[] = "User doesn't exist. Please try again.";
        }
    }

    include "header.php";
?>

<center>
    <br><br><br><br><br>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <p>
            <label for="input1">User Name:</label>
            <br><input type="text" name="username" id="input1" size="25" autocomplete="off" required>
        </p>
        <p>
            <label for="input2">Password:</label>
            <br><input type="password" name="password" id="input2" size="25" required>
        </p>
        <p>
            <input type="submit" value="Login">
        </p>
    </form> 


    <h10>Haven't registered? Register below.</h10>
    <form method="GET" action="registration.php">
        <p>
            <input type="submit" value="Register">
        </p>
    </form>

</center>

<?php include "footer.php"; ?>