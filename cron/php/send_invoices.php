<?php

include 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

// Database settings
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// SMTP settings
$smtpHost = getenv('SMTP_HOST');
$smtpPort = getenv('SMTP_PORT');
$smtpUsername = getenv('SMTP_USERNAME');
$smtpPassword = getenv('SMTP_PASSWORD');
$smtpEmail = getenv('SMTP_SENDER');
$smtpAuth = getenv('SMTP_AUTH') == 'true';

// Key settings
$secretKey = getenv('KEY');

// Host settings
$host = getenv('HOST');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');

$sql = "SELECT CDECENT_NOFAC, ADRESSEP_EMAIL FROM gt2i_facturedafi WHERE CDECENT_DTECDE = '$today' AND CDECENT_NOFAC != ''";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoiceNumber = $row['CDECENT_NOFAC'];
        $email = $row['ADRESSEP_EMAIL'];
        $timestamp = time();
        $tokenData = "$invoiceNumber|$timestamp";
        $hash = hash_hmac('sha256', $tokenData, $secretKey);
        $token = base64_encode("$invoiceNumber|$timestamp|$hash");
        $link = "$host/download_invoices.php?token=$token";

        $subject = "Facture du " . date('Y-m-d');
        $message = "Cher client,\n\nVeuillez télécharger votre facture via ce lien :\n$link\n\nMerci !";
        $headers = "From: $smtpEmail";

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = $smtpAuth;
        $mail->Port = $smtpPort;
        if ($smtpAuth) {
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
        }

        // Email content
        $mail->setFrom($smtpEmail);
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(false);

        // Send email
        if ($mail->send()) {
            echo "Email sent to: $email\n";
        } else {
            echo "Failed to send email to: $email. Error: " . $mail->ErrorInfo . "\n";
        }
    }

} else {
    echo "No invoices found for today\n";
}

$conn->close();
