<?php

// Token bot dari BotFather
$botToken = "YOUR_BOT_TOKEN";

// Mendapatkan data yang dikirimkan ke bot
$update = json_decode(file_get_contents("php://input"), true);

// Mendapatkan id chat dan teks yang dikirimkan
$chatID = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

// Membuat daftar respons sesuai dengan perintah
$responses = array(
    "/start" => "Halo! Selamat datang di bot sederhana.",
    "/help" => "Bot ini memiliki fungsi yang sangat sederhana. Anda bisa mencoba kirim /start untuk memulai atau /help untuk melihat bantuan.",
    "hello" => "Halo! Apa kabar?",
    "bye" => "Selamat tinggal! Sampai jumpa lagi."
);

// Mengirimkan respons berdasarkan teks yang diterima
if(isset($responses[$message])){
    sendMessage($chatID, $responses[$message]);
} else {
    sendMessage($chatID, "Maaf, saya tidak mengerti pesan Anda.");
}

// Fungsi untuk mengirim pesan ke bot API Telegram
function sendMessage($chatID, $message){
    global $botToken;
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatID&text=".urlencode($message);
    file_get_contents($url);
}
?>
