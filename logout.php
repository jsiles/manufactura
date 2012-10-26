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
		session_unregister("cliID");
        session_unregister("UserLogin");
        session_unregister("GroupID");
        header("Location: ./private/index.php");
        //exit;
        }
		else
		{
		session_unregister("cliID");
		session_unregister("UserRights");
		header("Location: index.php");
		exit;
		}

?>