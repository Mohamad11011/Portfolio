<?php

// Validate and sanitize input
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$emailHelp = isset($_POST['emailHelp']) ? filter_var($_POST['emailHelp'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '';
$comments = isset($_POST['comments']) ? htmlspecialchars($_POST['comments']) : '';

if (!empty($name) && !empty($phone) && !empty($emailHelp) && filter_var($emailHelp, FILTER_VALIDATE_EMAIL)) {
    // Set up email parameters
    $to_email = "mohamadhassan11011@gmail.com";
    $email_subject = "Inquiry From Contact Page";
    $vpb_message_body = nl2br("Dear Admin,\n
        The user whose detail is shown below has sent this message from " . $_SERVER['HTTP_HOST'] . " dated " . date('d-m-Y') . ".\n
        
        Name: " . $name . "\n
        Phone: " . $phone . "\n
        Email Address: " . $emailHelp . "\n
        Subject: " . $subject . "\n
        Message: " . $comments . "\n
        Thank You!\n\n");

    // Set up email headers
    $headers = "From: $name <$emailHelp>\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "Message-ID: <" . time() . rand(1, 1000) . "@" . $_SERVER['SERVER_NAME'] . ">" . "\r\n";

    // Try sending the email
    if (@mail($to_email, $email_subject, $vpb_message_body, $headers)) {
        $status = 'success';
        $output = "Congrats " . $name . ", your email message has been sent successfully! We will get back to you as soon as possible. Thanks.";
    } else {
        $status = 'error';
        $output = "Sorry, your email could not be sent at the moment. Please try again or contact this website admin to report this error message if the problem persists. Thanks.";
        // Log the mail function error
        error_log("Failed to send mail: " . error_get_last()['message']);
    }
} else {
    $status = 'error';
    $output = "Please fill in all required fields with valid information.";
}

echo json_encode(array('status' => $status, 'msg' => $output));
?>
