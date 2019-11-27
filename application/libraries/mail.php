<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    public function __construct()
    {
        require_once APPPATH."third_party/PHPMailer/Exception.php";
        require_once APPPATH."third_party/PHPMailer/PHPMailer.php";
        require_once APPPATH."third_party/PHPMailer/SMTP.php";
        
        log_message('Debug', 'PHPMailer class is loaded.');
    }
}

?>