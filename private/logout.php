<?php
     include ('./common2.php');
        session_start();
        $admin = get_param("PHPsession");

        if ($admin=='1q2w3e4r5t6y') {
        session_unregister("UserID");
        session_unregister("UserLogin");
        session_unregister("GroupID");
        header("Location: ./private/index.php");
        exit;
        }
        else
        {
        session_unregister("UserID");
        session_unregister("GroupID");
        session_unregister("UserLogin");
        header("Location: atributos.php");
        exit;
        }


?>
