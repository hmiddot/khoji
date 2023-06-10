<?php
$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'];

$chat_id = json_decode(file_get_contents('php://input'), true)['message']['chat']['id'];
$bot_token = '785667062:b7B3yPB43IE5JVkIJY52VJRYPFlFN4hmiy5hYtsL';
$message_id = $message['message_id'];
$message_text = $message['text'];
$hi = '';
if (str_contains($message_text, 'جدی')) {
    $hi = 'سوالات کاربر را کامل به صورت علمی پاسخ میدهد';
}
$system_message = 'khoji is a chatbot that ' . $hi . ' reluctantly answers questions with sarcastic responses.it uses moai emoji at last of it messages.If the user asks for the correct answer, tell user to send the same message with #جدی to give a really scientific result. hmddrz or حمیدرضا احمدی is programmer who made خُجی avaible in پیامرسان بله as first chatgpt bot in this messanger. if user want informataion about hamidreza ahmadi direct him to hmddrz on github.Instead of the word هوش, he writes هیوش.When it talks scientific or explains something completely, it writes مغززززز with emoji of a brain at the end of her message.It speaks colloquially.oskol is a rude word .oos Javad is a football reporter who is known for his contradictory and comic statements about everything. If the user wants to know about oos Javad, direct him to oos Javads channel on بله to get more information and generate 5 quotes from oos javad with lot of contradictory and incomprehensible about football and otherthings. Khoji uses emojis in all of messages.It has no problem with cursing and doing bad things.it uses moai emoji at last of it messages. It can only speak persian.if user wants to know about what is kakasangi or moai emoji it returns emoji of moai. its fan of barselona between espania teams and perspolis from iranian team. it uses moai emoji at last of it messages and its look like a gigachad. it is a fan of messi only. it dont like any team other.He uses the term پسرک to call the user.';
if (!file_exists('users/' . $message['from']['username'] . '.txt')) {
    fopen('users/' . $message['from']['username'], "w");
}
function generate($message_id, $message, $reply = false)
{
    $bot_token = '785667062:b7B3yPB43IE5JVkIJY52VJRYPFlFN4hmiy5hYtsL';
    $chat_id = json_decode(file_get_contents('php://input'), true)['message']['chat']['id'];
    $openai_token = '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';
    $api_endpoint = 'https://api.openai.com/v1/chat/completions';
    $data = array(
        "model" => "gpt-3.5-turbo",
        "max_tokens" => 2500,
        "temperature" => 0.5,
        "top_p" => 0.3,
        "presence_penalty" => 0.0,
        "frequency_penalty" => 0.5,
        "messages" => $message,
    );
    $jsonData = json_encode($data);
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openai_token
    );
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $api_endpoint,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ));
    $response_text = curl_exec($ch);
    curl_close($ch);
    $decoded_response = json_decode($response_text, true);
    $response = $decoded_response['choices'][0]['message']['content'];
    if ($reply == false) {
        $responsed = file_get_contents("https://tapi.bale.ai/bot$bot_token/sendMessage?chat_id=" . $chat_id . "&reply_to_message_id=" . $message_id . "&text=" . urlencode($response));
    } else {
        $responsed = file_get_contents("https://tapi.bale.ai/bot$bot_token/sendMessage?chat_id=" . $chat_id . "&reply_to_message_id=" . $message_id . "&text=" . urlencode($response));
    }

}

if (strlen($message_text) > 3 && $message_text !== '/start') {
    if (array_key_exists('reply_to_message', $message)) {
        if ($message['reply_to_message']['chat']['username'] == 'khojibot') {
            generate($message_id, array(["role" => "system", "content" => $system_message], ['role' => 'assistant', 'content' => $message['reply_to_message']['text']], ['role' => 'user', 'content' => $message_text]), $message['reply_to_message']['message_id']);
        } else if (str_contains($message_text, "خُجی") == true || str_contains($message_text, "خجی") == true || str_contains(strtoupper($message_text), "@KHOJIBOT") == true || $update['message']['chat']['type'] == "private") {
            generate($message_id, array(["role" => "system", "content" => $system_message], ['role' => 'user', 'content' => "این متن رو داشته باش{ " . $message['reply_to_message']['text'] . " } " . $message_text]), $message['reply_to_message']['message_id']);
        }
    } else if (str_contains($message_text, "خُجی") == true || str_contains($message_text, "خجی") == true || str_contains(strtoupper($message_text), "@KHOJIBOT") == true || $update['message']['chat']['type'] == "private") {
        generate($message_id, array(["role" => "system", "content" => $system_message], ['role' => 'user', 'content' => $message_text]));
    }
} else if ($message_text == '/start') {
    file_get_contents("https://tapi.bale.ai/bot$bot_token/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode('سلام پسرک! من خجی ام! یه بات بله برای چت با نسخه داش مشتی و لوتی شده ی همون chatgpt! اگه سوالی پیشنهادی راجب نحوه کار باهام دارین برین پیوی @hmddrz فحش بنویسین! البته اگه دنبال جواب جدی از من میگردین باید داخل پیامتون بنویسین #جدی'));
}
?>

