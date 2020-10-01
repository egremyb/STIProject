<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["logon"]);
header("Location:login.php");

