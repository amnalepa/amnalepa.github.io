<?php
$gmail_username = '';
$gmail_password = '';
$gmail_name = '';

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require("PHPMailer-master/src/PHPMailer.php");
require("PHPMailer-master/src/SMTP.php");
require("PHPMailer-master/src/Exception.php");

// require '../vendor/autoload.php';


/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

 if(!isset($_POST['submit'])){
   echo "error; you need to submit the form";
 }
 $email = $_POST['email'];
//
//  if(empty($email)) {
//    echo "Email is madatory";
//    exit;
//  }


//Create a new PHPMailer instance
$mail = new PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();
$mail->SMTPSecure = 'tls';

//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

//Set the hostname of the mail server
// $mail->Host = 'smtp.gmail.com';
// use
$mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
$mail->SMTPDebug = 3;

//Set the encryption mechanism to use - STARTTLS or SMTPS
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//Whether to use SMTP authentication
$mail->SMTPAuth = 'tls';

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $gmail_username;

//Password to use for SMTP authentication
$mail->Password = $gmail_password;

//Set who the message is to be sent from
$mail->setFrom($gmail_username, $gmail_name);

//Set an alternative reply-to address
$mail->addReplyTo($gmail_username, $gmail_name);

//Set who the message is to be sent to
$mail->addAddress($email, 'Subscription User');

//Set the subject line
$mail->Subject = 'Welcome to Cheatysheets!';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('welcome-email.html'), __DIR__);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
// $mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    if (save_mail($mail)) {
        echo "Message saved!";
    }
}

//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl', '*' ) to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    // $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';

    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    // $imapStream = imap_open($path, $mail->Username, $mail->Password);
    //
    // $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    // imap_close($imapStream);
    //
    // return $result;
}

// if(!isset($_POST['submit'])){
//   echo "error; you need to submit the form"
// }
// $email = $_POST['email']
//
// if(empty($email)) {
//   echo "Email is madatory";
//   exit;
// }
//
// // Import PHPMailer classes into the global namespace
// // These must be at the top of your script, not inside a function
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
//
// // Load Composer's autoloader
// require 'vendor/autoload.php';
//
// // Instantiation and passing `true` enables exceptions
// $mail = new PHPMailer(true);
//
// try {
//     //Server settings
//     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
//     $mail->isSMTP();                                            // Send using SMTP
//     $mail->Host       = 'smtp.example.com';                    // Set the SMTP server to send through
//     $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//     $mail->Username   = 'user@example.com';                     // SMTP username
//     $mail->Password   = 'secret';                               // SMTP password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
//     $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
//
//     //Recipients
//     $mail->setFrom('amnalepa1@gmail.com', 'Mailer');
//     $mail->addAddress($email, 'Form User');     // Add a recipient
//     // $mail->addAddress('ellen@example.com');               // Name is optional
//     $mail->addReplyTo('amnalepa1@gmail.com', 'Information');
//     // $mail->addCC('cc@example.com');
//     // $mail->addBCC('bcc@example.com');
//
//     // Attachments
//     // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//     // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//
//     // Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = 'Welcome to Cheatysheets!';
//     $mail->Body    = 'Thank you for your interest in Cheatysheets! We will send you an email when we release a new cheatysheet so you can check it out :)';
//     $mail->AltBody = 'Thank you for your interest in Cheatysheets! We will send you an email when we release a new cheatysheet so you can check it out :)';
//
//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }





//
// if(!isset($_POST['submit'])){
//   echo "error; you need to submit the form"
//
// }
// $email = $_POST['email']
//
// if(empty($email)) {
//   echo "Email is madatory";
//   exit;
// }
//
// $email_from = 'amnalepa1@gmail.com'
// $email_subject = 'Welcome to Cheatysheets!'
// $email_body = 'Thank you for your interest in Cheatysheets! We will send you an email when we release a new cheatysheet so you can check it out :)'
// $to = $email
// $headers = "From: $email_from \r\n";
// mail($to,$email_subject,$email_body,$headers)
 ?>
