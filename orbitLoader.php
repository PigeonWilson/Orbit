<?php
session_start();

// display errors
const SETUP_MODE = true;

// if true, no vpn and or active tools shall pass
const ALLOW_PROXY = false;

const MAX_FAILED_LOGON_ATTEMPTS = 5;

if (SETUP_MODE)
{
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    //$message = 'DEV<br/> MODE<br/> IS<br/> ACTIVE';
    $message = 'DEV';
    echo '<div style="color:red;font-size: x-large;font-weight: bolder; padding: 10px; background: black;">'.$message.'</div>';
}else{
    $message = '';
    echo '<div style="color:red;font-size: x-large;font-weight: bolder; padding: 10px; background: black;">'.$message.'</div>';
    error_reporting(0);
}

// database
$db_hostname = 'localhost';
$db_name = 'Orbit';
$db_username = 'root';
$db_password = '';

const PREVENT_DIRECT_FILE_ACCESS_CONST = true;
require_once ('Core' . DIRECTORY_SEPARATOR . 'constant.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Db.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Session.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Toolset.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Logger.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'SecurityEnforcer.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Router.php');
require_once ('Core' . DIRECTORY_SEPARATOR . 'Framework.php');

// Setup Framework
try {
    $_ = new Framework($db_hostname, $db_name, $db_username, $db_password);
}catch (Exception $e)
{
    if (SETUP_MODE)
    {
        echo $e->getMessage() . '<br/>';
        die('Framework initialization failed');
    }

    die();
}

$_->onPageLoad();