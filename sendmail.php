<?php
$to = "seventeam549@gmail.com";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $tmpFilePath = $_FILES['audio']['tmp_name'];
    $fileName = $_FILES['audio']['name'];
    $fileType = $_FILES['audio']['type'];

    $fileContent = chunk_split(base64_encode(file_get_contents($tmpFilePath)));
    $boundary = md5(time());

    $headers = "From: Web Recorder <noreply@yourdomain.com>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=utf-8\r\n\r\n";
    $body .= "فایل صوتی ضمیمه است.\r\n\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: {$fileType}; name=\"{$fileName}\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $fileContent . "\r\n";
    $body .= "--{$boundary}--";

    if (mail($to, "فایل صوتی جدید", $body, $headers)) {
        http_response_code(200);
        echo "ایمیل ارسال شد.";
    } else {
        http_response_code(500);
        echo "ارسال ایمیل با خطا مواجه شد.";
    }
} else {
    http_response_code(400);
    echo "درخواست نامعتبر.";
}