<?php
/*
by BaroN 
Ch : @sms_bm
*/
ob_start();
define("API_KEY", "توكن البوت");
function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}
$up = json_decode(file_get_contents("php://input"));
$msg = $up->message;
$text = $msg->text;
$ids = $msg->chat->id;
$id = $msg->from->id;
$get = file_get_contents("unll.txt");
$data = $up->callback_query->data;
$name = $up->callback_query->from->first_name;
$user = "@".$up->callback_query->from->username;
$txt = file_get_contents("text.txt");
$names = file_get_contents("name.txt");
$link  = file_get_contents("link.txt");
$sudo = "ايدي المطور";
$ch = "معرف القناة";
$list = json_encode([
'inline_keyboard'=>[
[
['text'=>'👍', 'callback_data'=>"like"],['text'=>'👎', 'callback_data'=>"dislike"]
],
[
['text'=>"$names", 'url'=>"$link"]
]
]
]);
function msg($ids, $text){
bot("sendMessage",[
"chat_id"=>$ids,
"text"=>$text,
'parse_mode'=>markdown,
'disable_web_page_preview'=>true,
]);
}
if($text == "/start" and $id == $sudo){
file_put_contents("unll.txt", "go");
msg($ids, "مرحبا بك عزيزي في بوت تصويت 📊
ارسل ما تريد تصويت عليه وسيتم نشرة 🤷‍♂");
}
if($text and $get == "go" and $id == $sudo){
file_put_contents("text.txt", $text);
file_put_contents("unll.txt", "1");
msg($ids, "- ارسل الان اسم للكيبورد 🔰 •");
}
if($text and $get == 1 and $id == $sudo){
file_put_contents("name.txt", $text);
file_put_contents("unll.txt", "2");
msg($ids, "- ارسل الان رابط للكيبورد 🔰 •");
}
if($text and $get == 2 and $id == $sudo){
file_put_contents("link.txt", $text);
file_put_contents("unll.txt", " ");
$txt = file_get_contents("text.txt");
$names = file_get_contents("name.txt");
$link  = file_get_contents("link.txt");
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"$txt",
'parse_mode'=>'MARKDOWN',
'disable_web_page_preview'=>'true',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[
['text'=>'👍', 'callback_data'=>"like"],['text'=>'👎', 'callback_data'=>"dislike"]
],
[
['text'=>"$names", 'url'=>"$link"]
]
]
])
]);
msg($ids, "- الان هاذه هيه رسالتك ☑️ •
- اذا تريد نشر ارسل (نعم)🔺•
- او تريد الغاء الامر ارسل (لا)🔻•");
}
if($text == "لا" and $id == $sudo){
file_put_contents("text.txt", " ");
file_put_contents("name.txt", " ");
file_put_contents("link.txt", " ");
file_put_contents("unll.txt", "stop");
msg($ids, "- تم الغاء الامر بنجاح 🔘 •");
}
if($text == "نعم" and $id == $sudo){
file_put_contents("unll.txt", "stop");
msg($ids, "- تم نشر المنشور بنجاح ☑️ •");
bot('sendMessage',[
'chat_id'=>$ch,
'text'=>"$txt",
'parse_mode'=>'MARKDOWN',
'disable_web_page_preview'=>'true',
'reply_markup'=>$list
]);
}
if($data == "like"){
bot('answerCallbackQuery',[
'callback_query_id'=>$up->callback_query->id,
'text'=>"- لقد تم تسجيل :: 👍 •",
'show_alert'=>true,
]);
bot("sendMessage",[
"chat_id"=>$sudo,
"text"=>"- هاذه الشخص اضغط :: 👍 •
- اسمه :: $name 🐝 •
- معرفه :: $user 🐛 •",
]);
}
if($data == "dislike"){
bot('answerCallbackQuery',[
'callback_query_id'=>$up->callback_query->id,
'text'=>"- لقد تم تسجيل :: 👎 •",
'show_alert'=>true,
]);
bot("sendMessage",[
"chat_id"=>$sudo,
"text"=>"- هاذه الشخص اضغط :: 👎 •
- اسمه :: $name 🐝 •
- معرفه :: $user 🐛 •",
]);
}
if($text != "/start" and $get == "stop" and $id == $sudo){
msg($ids, "- اذا تريد استخدام البوت مره اخره 🔄 •
- ارسل امر /start من جديد ☑️ •");
}
?>
