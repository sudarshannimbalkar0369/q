<?php
require_once __DIR__ . '/db.php';
session_destroy();
session_start();
flash_set('success', 'You have been logged out.');
redirect_to('index.php');
