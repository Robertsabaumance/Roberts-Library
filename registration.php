<?php
    session_start();

    $page = 'registration';

    require_once "connection.php";

    include "header.php"; 

    //check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        // Get values from form
        $UserName = trim($_POST['username']);
        $Password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);
        $firstName = trim($_POST['first_name']);
        $surname = trim($_POST['surname']);
        $address1 = trim($_POST['address1']);
        $address2 = trim($_POST['address2']);
        $city = trim($_POST['city']);
        $phone = trim($_POST['phone']);
        $mobile = trim($_POST['mobile']);

        $message = [];

        if (!$UserName || !$Password || !$confirmPassword || !$firstName || !$surname || !$address1 || !$city || !$phone || !$mobile) 
        {
            $message[] = "All fields marked with * are required.";
        }

        //ctype_digit, php function that checks whether string has only numeric values 0-9
        if (!ctype_digit($mobile) || strlen($mobile) !== 10) 
        {
            $message[] = "Mobile number must be numeric and exactly 10 digits.";
        }

        if (strlen($Password) < 6) 
        {
            $message[] = "Password must be at least 6 characters.";
        }

        if ($Password !== $confirmPassword) 
        {
            $message[] = "Passwords do not match.";
        }

        
        $stmt = $conn->prepare("SELECT Username FROM users WHERE Username = ?");
        $stmt->bind_param("s", $UserName); 
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) 
        {
            $message[] = "Username already exists. Please choose another or login with your existing username.";
        }

        if (empty($message))
        {
            // hash the password before storing
            $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

            //insert new user
            $stmt = $conn->prepare("INSERT INTO users (
                                                        Username, Password, 
                                                        FirstName, Surname, 
                                                        AddressLine1, AddressLine2,
                                                        City, Telephone, Mobile
                                                    ) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                                "sssssssss", $UserName, 
                                $hashedPassword, $firstName, 
                                $surname, $address1, 
                                $address2, $city, 
                                $phone, $mobile
                            );

            if ($stmt->execute()) 
            {
                //send to login page 
                header("Location: login.php");
                exit();

            } 
            else 
            {
                $message[] = "Unable to register user. User may already exist. Try to login.";
            }
        }
    }
?>

<center>

    <?php if (!empty($message)): ?>
        <ul class="feedback" style="color:red;">
            <?php foreach ($message as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h10>Already registered? Login below.</h10>
    <form method="GET" action="login.php">
        <p>
            <input type="submit" value="Login">
        </p>
    </form>
    
    <form method="POST" action="">
    <table style="margin:auto; text-align:left;">
        <tr>
            <td>
                <label for="input1">User Name*:</label><br>
                <input type="text" name="username" id="input1" size="25" autocomplete="off" required>
            </td>
            <td>
                <label for="input2">Password*:</label><br>
                <input type="password" name="password" id="input2" size="25" required>
            </td>
        </tr>
        <tr>
            <td>
                <label>Confirm Password*:</label><br>
                <input type="password" name="confirm_password" required>
            </td>
            <td>
                <label>First Name*:</label><br>
                <input type="text" name="first_name" required>
            </td>
        </tr>
        <tr>
            <td>
                <label>Surname*:</label><br>
                <input type="text" name="surname" required>
            </td>
            <td>
                <label>Address Line 1*:</label><br>
                <input type="text" name="address1" required>
            </td>
        </tr>
        <tr>
            <td>
                <label>Address Line 2:</label><br>
                <input type="text" name="address2">
            </td>
            <td>
                <label>City*:</label><br>
                <input type="text" name="city" required>
            </td>
        </tr>
        <tr>
            <td>
                <label>Phone*:</label><br>
                <input type="text" name="phone" required>
            </td>
            <td>
                <label>Mobile*:</label><br>
                <input type="text" name="mobile" required>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center; padding-top:10px;">
                <input type="submit" value="Register">
            </td>
        </tr>
    </table>
</form>
</center>

<?php include "footer.php"; ?>