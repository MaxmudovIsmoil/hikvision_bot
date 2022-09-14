<?php 
    date_default_timezone_set('Asia/Tashkent');

	// hikvision worker bot
	// define('BOT_TOKEN', '1785662477:AAEyJ4EmyJbDSb2XCUrWTjA6ZZaUc7CuoVw');
    // hikvision task bot
    define('BOT_TOKEN', '1737952789:AAHTik-cj7U-GqNEdL1UjQZc-eRYd8_E8J8');

    define('DB_SERVER', '10.70.34.14');
    define('DB_NAME', 'bot_db');
    define('DB_USER', 'ismoil');
    define('DB_PASSWORD', 'ismoil1996');


    $emoji = array(

    	'phone' => json_decode('"\ud83d\udcf1"'),
    	'ok'    => json_decode('"\uD83C\uDD97"'),
    	'icon_error' => json_decode('"\u26a0"'),
        'back'  => json_decode('"\u2b05"'),
        'done'  => json_decode('"\u2705"'),
        'no'    => json_decode('"\u26d4\ufe0f"'),
    );


    $string = array(
        'menu_yes'              => $emoji['done'] . " Бажарилди",
        'menu_no'               => $emoji['no'] . " Бажарилмади",
        'invalid_phone_number'  => "Telefon raqam noto'g'ri kiritildi.",
        'request_contact'       => "Telefon raqamni jo'natish",
        'menu_back'             => $emoji['back'] . " Orqaga qaytish",
    );

