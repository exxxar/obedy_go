<?php

namespace App\Actions;

use App\Contracts\PrepareChequeContract;
use App\Models\LotteryPromocode;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

class PrepareChequeAction implements PrepareChequeContract
{
    public function __invoke(Order $order): array
    {
        $arr = ["Без категории", "Стандарт", "Экспрес", "Премиум", "Собери сам"];
        $tmp = "";
        $products = $order->products;
        $total_weight = 0;
        $total_count = 0;
        $total_price = 0;
        foreach ($products as $index => $product) {

            $total_weight += $product->weight;
            $total_price += $product->pivot->quantity * $product->price;
            $total_count += $product->pivot->quantity;

            $tmp_titles = $product->title;
            $tmp_weight_str = $product->weight . "гр ";

            if (count($product->positions) > 0) {
                $tmp_titles .= " (";
                $tmp_weight_str .= " (";

                foreach ($product->positions as $index2 => $position) {
                    $tmp_position = (object)$position;
                    $tmp_titles .= $tmp_position->title;
                    $tmp_weight_str .= $tmp_position->weight . "гр";
                    if (count($product->positions) != ($index2 + 1)) {
                        $tmp_titles .= ", ";
                        $tmp_weight_str .= " + ";
                    }
                }

                $tmp_weight_str .= ")";
                $tmp_titles .= ")";
            }


            if (!$product->is_week) {
                $day_index = $product->day_index;

                $today = Carbon::now()->dayOfWeek;

                $order_date = $today >= 5 ? 7 - $today + $day_index : $day_index;

                $order_date = Carbon::now()->addDays($order_date);
                $order_date = ($order_date->day . "-" . $order_date->month . "-" . $order_date->year);
            } else
                $order_date = "Всю следующую неделю";

            if ($product->addition) {
                $order_date = Carbon::now()->addDay();
                $order_date = ($order_date->day . "-" . $order_date->month . "-" . $order_date->year);
            }
            $food_part_id = $product->food_part_id;

            $tmp .= sprintf("<tr><td>#%s</td> <td>%s<br>Заказ на <strong>%s</strong></td>  <td>%s</td> <td>%s руб.</td> <td> %s ед.</td></tr>",
                ($index + 1),
                ("<strong>" . ($arr[$food_part_id] ?? "Дополнительно") . "</strong>: " . $tmp_titles),
                $order_date,
                $tmp_weight_str,
                $product->price,
                $product->pivot->quantity
            );
        }

        $mpdf = new Mpdf();


        $current_date = Carbon::now("+3:00");

        $mpdf->WriteHTML("<h1>Счет на оплату</h1>");
        $mpdf->WriteHTML("<h6>Уникальный идентификатор заказа <strong style='color:darkred'>$order->number</strong></h6>");
        $mpdf->WriteHTML('<h3>Сервис "ОбедыGO"</h3>');
        $mpdf->WriteHTML('<hr>');
        $mpdf->WriteHTML("<ul>
 <li>Имя заказчика <strong>$order->name</strong></li>
 <li>Телефон заказчика <strong>$order->phone</strong></li>
 <li>Адрес заказчика <strong>$order->address</strong></li>
 <li>Дополнительная информация от заказчика <strong>$order->message</strong></li>
 <li>Сумма заказа <strong>$total_price руб.</strong></li>
 <li>Масса заказа <strong>$total_weight гр</strong></li>
 <li>Количество позиций в заказе <strong>$total_count ед.</strong></li>
 <li>Бесплатная доставка по Ворошиловскому району</li>
 <li>Минимальный заказ - от <strong>3х</strong> порций</li>
 <li>Дата и время осуществления заказа <strong>$current_date!</strong></li>
</ul>
<hr>
<h3>Ваш заказ состоит из следующих позиций:</h3>");

        $order->summary_price = $total_price;
        $order->save();

        $result = [
            'total_weight' => $total_weight,
            'total_count' => $total_count,
        ];

        $promo_count = round($total_price / 100);
        $code = substr((string)Str::uuid(), 0, 16);
        LotteryPromocode::create([
            'code' => $code,
            'max_activation_count' => $promo_count,
            'current_activation_count' => 0,
        ]);

        $mpdf->WriteHTML("<table>

<tr>
<td style='width: 50px;'><strong>№</strong></td>
<td style='width: 250px;'><strong>Название</strong></td>
<td style='width: 250px;'><strong>Масса, гр</strong></td>
<td style='width: 100px;'><strong>Цена, руб</strong></td>
<td style='width: 100px;'><strong>Количество, шт</strong></td>
</tr>

$tmp

</table>
<hr>
<h3>Ваш промокод для участия в акциях:</h3>
<p>$code - всего доступно <strong>$promo_count</strong> активаций </p>
<h4>Команда Обеды<span style='color:red'>GO</span> благодарит Вас за использование нашего сервиса! Мы стараемся быть лучше для Вас!</h4>
");

        $file = $mpdf->Output("order-$order->phone.pdf", \Mpdf\Output\Destination::STRING_RETURN);

        Storage::disk('public')->put("order-$order->phone.pdf", $file);

        $result['cheque'] = $mpdf->Output("order-$order->phone.pdf", 'I');

        return $result;
    }
}
