<?php

namespace App\Http\Controllers;

use App\Contracts\WinLotteryContract;
use App\Events\ChangeLotteryEvent;
use App\Http\Requests\Lotteries\PickPlaceRequest;
use App\Http\Resources\LotteryResource;
use App\Models\Lottery;
use App\Models\LotteryPromocode;


class LotteryController extends Controller
{
    public function getLotteryList()
    {
        $lotteries = Lottery::where('is_active', true)->where('is_complete', false)->get();
        return response()->json(LotteryResource::collection($lotteries));
    }

    public function getLottery($id)
    {
        $lottery = Lottery::where('id', $id)->first();
        return response()->json(new LotteryResource($lottery));
    }

    public function pickPlace(PickPlaceRequest $request, WinLotteryContract $winLotteryAction)
    {

        $promocode = LotteryPromocode::where('code', $request->code)->first();

        $lottery = Lottery::find($request->lottery_id);

        $index = $request->selected_place;

        $tmp_occupied_places = $lottery->occupied_places;
        $tmp_occupied_places[] = $index;
        $lottery->occupied_places = $tmp_occupied_places;
        $lottery->free_place_count -= 1;
        $lottery->save();

        $promocode->name = $request->name;
        $promocode->phone = $request->phone;
        $promocode->email = $request->email;
        $promocode->current_activation_count += 1;
        $promocode->lottery_id = $request->lottery_id;
        $promocode->save();

        $winLotteryAction($lottery, $promocode, $index);

        ChangeLotteryEvent::dispatch($promocode->lottery_id);

        return response()->json([
            'current_slot_count' => ($promocode->max_activation_count - $promocode->current_activation_count)
        ]);
    }
}
