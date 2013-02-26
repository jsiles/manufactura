<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
 		include ('./common.php');
		session_start();
		$admin = get_param("PHPsession");

		if ($admin=='1q2w3e4r5t6y') {
		unset($_SESSION["cliID"]);
        unset($_SESSION["UserLogin"]);
        unset($_SESSION["GroupID"]);
        header("Location: ./private/index.php");
        //exit;
        }
		else
		{
		unset($_SESSION["cliID"]);
		unset($_SESSION["UserRights"]);
		header("Location: index.php");
		exit;
		}

?>