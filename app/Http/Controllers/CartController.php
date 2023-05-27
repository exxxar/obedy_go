<?php

namespace App\Http\Controllers;

use App\Actions\Cart\PrintReportAction;
use App\Actions\Cart\SaveCartAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function changeCart(Request $request, SaveCartAction $saveCartAction)
    {
        if(Auth::check()){
            $saveCartAction($request->product, $request->action);
            return response()->json(['status' => 'ok']);
        }
        return response([], 403);

    }

    public function saveCart(Request $request, SaveCartAction $saveCartAction)
    {
        if(Auth::check()){
            foreach ($request->products as $product){
                $saveCartAction($product, 'add');
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'ok']);

    }

    public function clearCart(){
        $user = Auth::user();
        if($user){
            $user->products()->detach();
            return response()->json(['status' => 'ok']);
        }
        return response([], 403);
    }

    public function printReport(PrintReportAction $printReportAction){
        if(Auth::check()){
            return $printReportAction();
        }
        return response([], 403);

    }
}
