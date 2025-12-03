<?php
require_once '../config/config.php';
session_destroy();
header("Location: " . base_url('/auth/login.php'));
exit;
