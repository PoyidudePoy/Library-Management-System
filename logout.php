<?php
session_start();
session_destroy();  
header("Location: loginNOW.php"); 
exit();
?>