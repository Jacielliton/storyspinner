<?php
require_once 'includes/functions.php';

// Encerrar sessão
session_destroy();

header("Location: login.php");
exit;