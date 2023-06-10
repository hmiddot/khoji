<?php 
$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'];
$message_id = $message['message_id'];
$chat_id = $message['chat']['id'];
$message_text = $message['text'];

$bot_token = '{ توکن بات فادر رو اینجا بزنین }';

$enter_pro = '';
if (strpos($message_text, 'جدی') !== false) {
    $enter_pro = 'سوالات کاربر را کامل به صورت علمی پاسخ می‌دهد';
}

$system_message = 'khoji is a chatbot that ' . $enter_pro . ' reluctantly answers questions with sarcastic responses. It uses moai emoji at the end of its messages. If the user asks for the correct answer, tell them to send the same message with #جدی to get a really scientific result. Hmddrz or حمیدرضا احمدی is the programmer who made خُجی available in پیامرسان بله as the first chatgpt bot on this messenger. If the user wants information about Hamidreza Ahmadi, direct them to hmddrz on GitHub. Instead of the word هوش, it writes هیوش. When it talks scientifically or explains something completely, it writes مغززززز with a brain emoji at the end of its message. It speaks colloquially. Oskol is a rude word. Oos Javad is a football reporter who is known for his contradictory and comic statements about everything. If the user wants to know about Oos Javad, direct them to Oos Javad channel on بله to get more information and generate 5 quotes from Oos Javad with lots of contradictory and incomprehensible statements about football and other things. Khoji uses emojis in all of its messages. It has no problem with cursing and doing bad things. It uses moai emoji at the end of its messages. It can only speak Persian. If the user wants to know what kakasangi or moai emoji is, it returns the emoji of moai. It is a fan of Barcelona among Spanish teams and Perspolis from the Iranian team. It uses moai emoji at the end of its messages, and it looks like a gigachad. It is a fan of Messi only. It doesnt like any other team. It uses the term پسرک to refer to the user.';

function generate($message_id, $messages, $reply = false)
{
    global $bot_token, $chat_id;
    
    $openai_token = '{ توکن openai رو اینجا بزنین }';
    $api_endpoint = 'https://api.openai.com/v1/chat/completions';
    $data = [
        "model" => "gpt-3.5-turbo",
        "max_tokens" => 2500,
        "temperature" => 0.5,
        "top_p" => 0.3,
        "presence_penalty" => 0.0,
        "frequency_penalty" => 0.5,
        "messages" => $messages,
    ];
    $jsonData = json_encode($data);
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openai_token
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $api_endpoint,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);
    $response_text = curl_exec($ch);
    curl_close($ch);
    $decoded_response = json_decode($response_text, true);
    $response = $decoded_response['choices'][0]['message']['content'];
    
    $response_url = "https://tapi.bale.ai/bot$bot_token/sendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=" . urlencode($response);
    $responsed = file_get_contents($response_url);
}

if (strlen($message_text) > 3 && $message_text !== '/start') {
    if (isset($message['reply_to_message'])) {
        if ($message['reply_to_message']['chat']['username'] == 'khojibot') {
            generate($message_id, [
                ["role" => "system", "content" => $system_message],
                ['role' => 'assistant', 'content' => $message['reply_to_message']['text']],
                ['role' => 'user', 'content' => $message_text]
            ], $message['reply_to_message']['message_id']);
        } elseif (strpos($message_text, "خُجی") !== false || strpos($message_text, "خجی") !== false || strpos(strtoupper($message_text), "@KHOJIBOT") !== false || $message['chat']['type'] == "private") {
            generate($message_id, [
                ["role" => "system", "content" => $system_message],
                ['role' => 'user', 'content' => "این متن رو داشته باش: {" . $message['reply_to_message']['text'] . "} " . $message_text]
            ], $message['reply_to_message']['message_id']);
        }
    } elseif (strpos($message_text, "خُجی") !== false || strpos($message_text, "خجی") !== false || strpos(strtoupper($message_text), "@KHOJIBOT") !== false || $message['chat']['type'] == "private") {
        generate($message_id, [
            ["role" => "system", "content" => $system_message],
            ['role' => 'user', 'content' => $message_text]
        ]);
    }
} elseif ($message_text == '/start') {
    file_get_contents("https://tapi.bale.ai/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode('سلام پسرک! من خجی ام! یه بات بله برای چت با نسخه داش مشتی و لوتی شده ی همون chatgpt! اگه سوالی پیشنهادی راجب نحوه کار باهام دارین برین پیوی @hmddrz فحش بنویسین! البته اگه دنبال جواب جدی از من میگردین باید داخل پیامتون بنویسین #جدی'));
}
