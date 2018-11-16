<?php
session_start();
echo $_SESSION[$_GET['key']];
?>