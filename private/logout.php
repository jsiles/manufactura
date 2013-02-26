<?php
     include ('./common2.php');
        session_start();
        $admin = get_param("PHPsession");

        if ($admin=='1q2w3e4r5t6y') {
        unset($_SESSION["UserID"]);
        unset($_SESSION["UserLogin"]);
        unset($_SESSION["GroupID"]);
        header("Location: ./private/index.php");
        exit;
        }
        else
        {
        unset($_SESSION["UserID"]);
        unset($_SESSION["GroupID"]);
        unset($_SESSION["UserLogin"]);
        header("Location: atributos.php");
        exit;
        }


?>
