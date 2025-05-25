<?php
require_once 'classes/Cookies.php';

// Clear the user cookie by setting it with past expiration
Cookie::delete('user');

// Optionally, you can also clear the session if you use sessions
// session_start();
// session_destroy();

header('Location: index.php');
exit;
