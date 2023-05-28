<?php

namespace App\Http\Controllers;

use App\Http\Requests\Callback\CallbackRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\File;

class CallbackController extends Controller
{

    public function sendMessage(CallbackRequest $request)
    {
        if(!$request->hasFile('files')) {
            $message = sprintf("<b>Заявка:</b>\nТелефон: %s\nФ.И.О.: %s\nСообщение: %s",
                $request->phone,
                $request->name,
                $request->message
            );
            if($request->typeValue == 0)
                $message .= sprintf("\nАдрес: %s", $request->address);

            Telegram::sendMessage([
                'chat_id' => config('telegram.channel'),
                'parse_mode' => 'HTML',
                'text' => $message
            ]);
        }else{
            $names = [];
            foreach ( $request->file('files') as $file) {
                $name = "record-obedy-" . time() . ".mp3";
                $names[] = "/uploads/$name";
                Storage::disk('public')->put('uploads/'.$name, File::get($file));
                 Telegram::sendAudio([
                     'chat_id' => config('telegram.channel'),
                     "caption" => "<b>Голосовая заявка от пользователя $request->name</b>\nНомер телефона:<i> $request->phone </i>",
                     'parse_mode' => 'HTML',
                     'audio' => InputFile::create(storage_path('app/public') . "/uploads/$name"),
                 ]);
            }
            Storage::disk('public')->delete($names);
        }
        return response([], 200);
    }

    public function sendVoice(Request $request)
    {

        $phone = $request->phone ?? "+380710000000";
        $username = $request->name ?? "-";

        $files = $request->file('files');

        array_map('unlink', glob(storage_path("app/public/uploads/*")));

        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $name = "record-obedy-" . time() . ".mp3";
                $file->storeAs("/uploads/", $name);
                /* Telegram::sendAudio([
                     'chat_id' => env("TELEGRAM_FASTORAN_OBEDY_GO_CHANNEL"),
                     "caption" => "<b>Голосовая заявка от пользователя [$username]</b>\nНомер телефона:<i> $phone </i>",
                     'parse_mode' => 'HTML',
                     'audio' => \Telegram\Bot\FileUpload\InputFile::create(storage_path('app/public') . "/uploads/$name"),
                 ]);*/

                Storage::delete("/uploads/$name");
            }
        }


        return "success";
    }
}
