<?php
require 'PHPMailerAutoload.php';
$name=$_POST['name'];
$email=$_POST['email'];
$feedback=$_POST['feedback'];

/*
$mail = new PHPMailer;
$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'sunboykenneth@gmail.com';                 // SMTP username
$mail->Password = 'dragon8157035';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From = 'sunboykenneth@gmail.com';
$mail->FromName = 'Kenneth';
$mail->addAddress($email, $name);     // Add a recipient

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Feedback from customer for Bob\'s Department';

$mailbody = "From Customer: ".$name."\n".
            "Email Address: ".$email."\n\n".
            $feedback."\n";

$mail->Body    = $mailbody;

if(!$mail->send()) {
        echo 'Your feedback could not be sent.';
        echo 'Mail Server Error: ' . $mail->ErrorInfo;
} else {
        echo 'Your feedback has been sent successfully at <br />';
        echo date("l jS \of F Y h:i:s A");
}
*/

if(empty($email)) {
    echo "You did not fill the email.<br />";    
}
else {
    if(eregi('^[a-zA-Z1-9\-_\.]+@[a-zA-Z1-9\-_]+(\.[a-zA-Z1-9\-_]+)+$', $email)){
        $splitArr = split('@|\.', $email);
        $num = count($splitArr);
        for($i = 0; $i < $num; ++$i)
            echo $splitArr[$i].'<br />';
        echo '<br />';
    }
    else
        echo "Invalid email address";
}

?>
<html>
<head>
<title>Bob's Auto Parts - Feedback Submitted</title>
</head>
<body>
<h1>Feedback submitted</h1>
<p>Your feedback has been sent.</p>
</body>
</html>
