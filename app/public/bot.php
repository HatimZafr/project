<?php

define('BOT_TOKEN', '7052133522:AAGG8gq2TiH54hhsHCwfqu8XjhklAlmNV30');

// Fungsi untuk mengirim pesan dengan gambar ke Telegram
function sendPhoto($chat_id, $photo) {
    $url = 'https://api.telegram.org/bot' . BOT_TOKEN . '/sendPhoto';
    $post_fields = array(
        'chat_id'   => $chat_id,
        'photo'     => new CURLFile($photo)
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// Fungsi untuk membuat sertifikat
function generateCertificate($name) {
    $canvas = imagecreatetruecolor(1836, 3264);
    $certificateImage = imagecreatefrompng('sertifikat.png');

    // Gambar sertifikat ke dalam canvas
    imagecopyresampled($canvas, $certificateImage, 0, 0, 0, 0, 1836, 3264, imagesx($certificateImage), imagesy($certificateImage));

    // Tambahkan teks nama
    $text_color = imagecolorallocate($canvas, 255, 255, 255);
    $font = 'arial.ttf'; // Path ke file font Arial (atau gunakan path font yang sesuai)
    $font_size = 100;
    $x = 918;
    $y = 2950;
    imagettftext($canvas, $font_size, 0, $x, $y, $text_color, $font, $name);

    // Simpan sertifikat sebagai file PNG
    $certificate_file = 'certificate.png';
    imagepng($canvas, $certificate_file);

    // Hapus sumber daya gambar dari memori
    imagedestroy($canvas);
    imagedestroy($certificateImage);

    return $certificate_file;
}

// Ambil data dari webhook
$update = json_decode(file_get_contents('php://input'), true);

// Jika ada pesan yang diterima
if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];

    // Jika pengguna mengirim pesan
    if (isset($update['message']['text'])) {
        $text = $update['message']['text'];

        // Jika pengguna mengirim nama, buat sertifikat dan kirim
        if (strpos($text, '/start') !== false) {
            // Meminta pengguna untuk memasukkan nama
            $message = 'Masukkan nama Anda untuk sertifikat:';
            file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=" . $chat_id . "&text=" . $message);
        } else {
            // Buat sertifikat dengan nama yang diberikan
            $certificate_file = generateCertificate($text);

            // Kirim sertifikat ke pengguna
            sendPhoto($chat_id, $certificate_file);

            // Hapus sertifikat setelah dikirim
            unlink($certificate_file);
        }
    }
}

?>
