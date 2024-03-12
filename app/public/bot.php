<?php

// Ganti 'TOKEN_BOT_ANDA' dengan token bot Telegram Anda
define('TOKEN', '7052133522:AAGG8gq2TiH54hhsHCwfqu8XjhklAlmNV30');
define('API_URL', 'https://api.telegram.org/bot'.TOKEN.'/');

// Mendapatkan update dari Telegram
$update = json_decode(file_get_contents('php://input'), true);

// Pesan yang diterima dari pengguna
$message = $update['message'];

// ID pengirim pesan
$chat_id = $message['chat']['id'];

// Isi pesan yang diterima
$text = $message['text'];

// Menangani perintah "/start"
if ($text == '/start') {
    $response = 'Halo! Saya adalah bot sederhana. Silakan kirimkan pesan kepada saya.';
} else {
    $response = 'Terima kasih atas pesan Anda: '.$text;
}

// Mengirimkan balasan ke pengguna
file_get_contents(API_URL.'sendmessage?chat_id='.$chat_id.'&text='.$response);
