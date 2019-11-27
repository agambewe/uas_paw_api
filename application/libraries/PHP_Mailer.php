<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHP_Mailer
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
		require_once APPPATH."third_party/PHPMailer/Exception.php";
        require_once APPPATH."third_party/PHPMailer/PHPMailer.php";
		require_once APPPATH."third_party/PHPMailer/SMTP.php";

        $objMail = new PHPMailer;
        return $objMail;
    }
}

?>