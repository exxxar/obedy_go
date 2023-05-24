<?php

namespace App\Actions\Confirm;

use App\Events\SendSmsEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfirmCodeAction
{

    public function __invoke($phone) :void
    {
        $code = strval(mt_rand(1000, 9999));
        event(new SendSmsEvent($phone, "Код для подтверждения заказа в ОбедыGO - " . $code));
        DB::table('password_reset_tokens')->where('phone', $phone)->delete();
        DB::table('password_reset_tokens')->insert([
            'phone' => $phone,
            'token' => $code
        ]);

    }
}
