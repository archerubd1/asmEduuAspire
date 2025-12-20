<?php


use PHPMailer\PHPMailer\PHPMailer;

if($_POST)
{

    require_once "PHPMailer/Exception.php";
    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";

    $mail = new PHPMailer();


    $your_email = "contact@megaone.ads";  //Replace with recipient email address

    $to_Email   	= $your_email;

    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {

        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error',
            'text' => 'Request must come from Ajax'
        ));

        die($output);
    }

    //check $_POST vars are set, exit if any missing
    if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userMessage"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }

    //Sanitize input data using PHP filter_var().
    $user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
    $user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $user_Phone =  $_POST["userPhone"];
    //$user_Subject =  $_POST["userSubject"];
    $user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);

    //additional php validation
    if(strlen($user_Name)<3) // If length is less than 3 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }

    if(strlen($user_Message)<5) //check emtpy message
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }


       //Server settings
//    $mail->isSMTP();                                            // Send using SMTP
//    $mail->Host       = 'smtp.googlemail.com';                    // Set the SMTP server to send through
//    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//    $mail->Username   = 'website@gmail.com';                     // SMTP username
//    $mail->Password   = 'your password';                         // SMTP password
//    $mail->SMTPSecure = 'TLS';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
//    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
     $mail->setFrom($user_Email,$user_Name);
     $mail->addAddress($your_email, 'Theme Industry');     // Add a recipient
     $mail->addReplyTo($your_email, 'Information');



    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'New Contact Inquiry from your Website';
    $mail->Body  = "<h4 style='text-align: center;padding: 25px 15px;background-color: #0c6c9e;color: #FFFFFF;font-size:16px;width:90%;border-radius: 10px;'>Hi There! You have a new inquiry from your website.</h4><br><br>";


    $mail->Body .= "<strong>Name: </strong>". $user_Name ."<br>";
    $mail->Body .= "<strong>Email: </strong>". $user_Email ."<br>";
    $mail->Body .= "<strong>Phone: </strong>". $user_Phone ."<br>";
    $mail->Body .= "<strong>Message: </strong>". $user_Message ."<br>";

    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send())
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP version or mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .' Thank you for contacting us.'));
        die($output);
    }

}
?>