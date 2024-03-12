<?php

// Token bot Telegram
$botToken = '7052133522:AAGG8gq2TiH54hhsHCwfqu8XjhklAlmNV30';

// URL API untuk membuat sertifika
$apiUrl = 'https://karyakita.infinityfreeapp.com/raya1.html?teks=';

// Fungsi untuk mengirim pesan ke bot Telegram
function sendMessage($chatId, $message) {
    global $botToken;
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);
    file_get_contents($url);
}

// Fungsi untuk mengirim sertifikat ke pengguna
function sendCertificate($chatId, $name) {
    global $apiUrl;
    $certificateUrl = $apiUrl . urlencode($name);
    // Mendownload sertifikat dari URL
    $image = file_get_contents($certificateUrl);
    // Mengirim gambar sebagai file ke bot Telegram
    $url = "https://api.telegram.org/bot$botToken/sendPhoto";
    $postFields = array('chat_id' => $chatId, 'photo' => $image);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    $output = curl_exec($ch);
    curl_close($ch);
}

// Mendapatkan input dari pengguna
$update = json_decode(file_get_contents('php://input'), true);
if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $message = $update['message']['text'];

    // Memproses perintah dari pengguna
    if (strpos($message, '/start') !== false) {
        sendMessage($chatId, "Silakan masukkan nama Anda:");
    } elseif (strpos($message, '/nama') !== false) {
        // Mengirim sertifikat setelah pengguna memasukkan nama
        $name = trim(str_replace('/nama', '', $message));
        sendCertificate($chatId, $name);
    }
}

?>
