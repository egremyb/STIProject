<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["logon"]);
unset($_SESSION['role']);
header("Location:login.php");

