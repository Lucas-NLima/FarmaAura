<?php
session_start();
session_destroy();
header("Location: app/view/login.php");
exit;
