<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["logon"]);
unset($_SESSION['role']);
unset($_SESSION['token']);
header("Location:login.php");

