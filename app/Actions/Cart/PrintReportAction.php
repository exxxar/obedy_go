<?php

namespace App\Actions\Cart;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PrintReportAction
{
    public function __invoke()
    {
        $user = User::find(Auth::id());
        $report = '';
        $arr = ["Без категории", "Стандарт", "Экспрес", "Премиум", "Собери сам"];
        $products = $user->products;
        $productIndex =1;
        foreach ($products as $index => $product) {
            $tmp_titles = $product->title;

            if (count($product->positions) > 0) {
                $tmp_titles .= " (";

                foreach ($product->positions as $index2 => $position) {
                    $tmp_position = (object)$position;
                    $tmp_titles .= $tmp_position->title;
                    if (count($product->positions) != ($index2 + 1)) {
                        $tmp_titles .= ", ";
                    }
                }
                $tmp_titles .= ")";
            }

            $food_part_id = $product->food_part_id;
            foreach (json_decode($product->pivot->users, true) as $userIndex => $user){
                $report .= sprintf("<tr><td>#%s</td> <td>%s</td><td>%s руб.</td> <td> %s ед.</td><td>%s</td><td>%s</td></tr>",
                    $productIndex,
                    ("<strong>" . ($arr[$food_part_id] ?? "Дополнительно") . "</strong>: " . $tmp_titles),
                    $product->price * $user['quantity'],
                    $user['quantity'],
                    $user['name'],
                    $user['phone']
                );
                $productIndex++;
            }
        }

        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML("<h1>Отчет по добавленным продуктам</h1>");
        $mpdf->WriteHTML('<h3>Сервис "ОбедыGO"</h3>');
        $mpdf->WriteHTML("<table>
<tr>
<td style='width: 50px;'><strong>№</strong></td>
<td style='width: 350px;'><strong>Продукт</strong></td>
<td style='width: 150px;'><strong>Цена, руб</strong></td>
<td style='width: 150px;'><strong>Количество, шт</strong></td>
<td style='width: 100px;'><strong>Имя</strong></td>
<td style='width: 100px;'><strong>Телефон</strong></td>
</tr>

$report

</table>
<hr>
<h4>Команда Обеды<span style='color:red'>GO</span> благодарит Вас за использование нашего сервиса! Мы стараемся быть лучше для Вас!</h4>
");
        $file = $mpdf->Output("report-".Auth::id().".pdf", Destination::STRING_RETURN);
        Storage::disk('public')->put("report-".Auth::id().".pdf", $file);
        return $mpdf->Output("report-".Auth::id().".pdf", 'I');
    }
}
