<?php
    session_start();
    session_unset();   // deletes any saved data
    session_destroy(); // destroy the session

    header("Location: login.php");
    exit();
?>