<?php

// This is a standalone script to test email sending without Laravel
// Run it from the command line: php test-email.php

// Email configuration
$smtpHost = 'smtp.gmail.com';
$smtpPort = 587;
$smtpUsername = 'hakammedtaha@gmail.com';
$smtpPassword = 'cnzsnwuqkegtbggd';
$fromEmail = 'noreply@fishingtackleshop.com';
$fromName = 'SDK-AquaPro';
$toEmail = 'hakammedtaha@gmail.com'; // Change this to your email

// Create a message
$subject = 'Test Email from Fishing Tackle Shop';
$message = "This is a test email sent from the Fishing Tackle Shop application.\n\n";
$message .= "This email was sent using a direct PHP mail script to test SMTP configuration.\n";
$message .= "Time: " . date('Y-m-d H:i:s');

// Additional headers
$headers = "From: {$fromName} <{$fromEmail}>\r\n";
$headers .= "Reply-To: {$fromEmail}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Output test information
echo "Testing email configuration:\n";
echo "SMTP Host: {$smtpHost}\n";
echo "SMTP Port: {$smtpPort}\n";
echo "SMTP Username: {$smtpUsername}\n";
echo "From: {$fromName} <{$fromEmail}>\n";
echo "To: {$toEmail}\n";
echo "Subject: {$subject}\n\n";

// Try to send the email using PHP's mail function
echo "Attempting to send email using PHP mail()...\n";
$mailResult = mail($toEmail, $subject, $message, $headers);

if ($mailResult) {
    echo "Email appears to have been sent successfully using mail().\n";
} else {
    echo "Failed to send email using mail().\n";
}

// If you have the PHPMailer library, you could use it here
// This is just a placeholder to show how you would use it
echo "\nNote: For more reliable SMTP testing, consider using PHPMailer library.\n";
echo "Example code for PHPMailer:\n";
echo "\n"
    . "use PHPMailer\PHPMailer\PHPMailer;\n"
    . "use PHPMailer\PHPMailer\Exception;\n\n"
    . "\$mail = new PHPMailer(true);\n"
    . "try {\n"
    . "    \$mail->isSMTP();\n"
    . "    \$mail->Host = '{$smtpHost}';\n"
    . "    \$mail->SMTPAuth = true;\n"
    . "    \$mail->Username = '{$smtpUsername}';\n"
    . "    \$mail->Password = '{$smtpPassword}';\n"
    . "    \$mail->SMTPSecure = 'tls';\n"
    . "    \$mail->Port = {$smtpPort};\n\n"
    . "    \$mail->setFrom('{$fromEmail}', '{$fromName}');\n"
    . "    \$mail->addAddress('{$toEmail}');\n\n"
    . "    \$mail->isHTML(false);\n"
    . "    \$mail->Subject = '{$subject}';\n"
    . "    \$mail->Body = '{$message}';\n\n"
    . "    \$mail->send();\n"
    . "    echo 'Email has been sent';\n"
    . "} catch (Exception \$e) {\n"
    . "    echo 'Email could not be sent. Error: ', \$mail->ErrorInfo;\n"
    . "}\n";

echo "\nFinished testing email configuration.\n";