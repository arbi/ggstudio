<?php
session_start();

use ggs\api\Service\Request;
 
require 'init_autoloader.php';

// request route mapper
$request = new Request();
