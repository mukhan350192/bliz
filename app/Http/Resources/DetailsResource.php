<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $from = CityResource::collection(City::where('id', $this->from)->get());
        $to = CityResource::collection(City::where('id', $this->to)->get());
        $array = [
            'from' => $this->from,
            'to' => $this->to,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
        if (isset($this->volume)) {
            $array['volume'] = $this->volume;
        }
        if (isset($this->net)) {
            $array['net'] = $this->net;
        }
        if (isset($this->middle)) {
            $array['middle'] = $this->middle;
        }
        if (isset($this->distance)) {
            $array['distance'] = $this->distance;
        }
        if (isset($this->duration)) {
            $array['duration'] = $this->duration;
        }
        if (isset($this->from_string)) {
            $array['from_string'] = $this->from_string;
        }
        if (isset($this->to_string)) {
            $array['to_string'] = $this->to_string;
        }
        if (isset($this->title)) {
            $array['title'] = $this->title;
        }
        $list_transport = [
            1 => 'Авто',
            2 => 'Ж/Д',
            3 => 'Авиа',
            4 => 'Морской',
            5 => 'Мультимодальные',
        ];
        if (isset($this->type_transport)) {
            $array['type_transport'] = $list_transport[$this->type_transport];
        }
        $list_sub_transport = [
            1 => 'Тент',
            2 => 'Изотерм',
            3 => 'Цельномет.',
            4 => 'Рефрижератор',
            5 => 'Автобус грузопас.',
            6 => 'Автобус люкс',
            7 => 'Автовоз',
            8 => 'Бензовоз',
            9 => 'Контейнеровоз',
            10 => 'Открытая',
            11 => 'Самосвал',
            12 => 'Трал',
            13 => 'Крытый вагон',
            14 => 'Полувагон',
            15 => 'Платформа',
            16 => 'Цистерна',
            17 => 'Рефрижератор',
            18 => 'Хоппер',
            19 => 'Думпкар',
            20 => 'Фитинговая платформа',
            21 => 'Вагон бункерного типа',
            22 => 'Транспортер',
            23 => 'Автомобилевоз',
            24 => 'Вагон-кенгуру',
            25 => 'Вагон-изотермический',
            26 => 'Вагон-Ледник',
            27 => 'Вагон-Термос',
            28 => 'ЦМГВ',
            29 => 'Простые перевозки',
            30 => 'Попутные перевозки',
            31 => 'Челночные перевозки',
            32 => 'Сборные перевозки',
            33 => 'Контейнерные перевозки',
            34 => 'Морской фрахт',
            35 => 'Навалочные грузы',
            36 => 'Ро-ро перевозки',
            37 => 'Перевозки «дверь-дверь»',
        ];
        if (isset($this->type_sub_transport)) {
            $arr = explode(',', $this->type_sub_transport);
            $s = array_filter($arr,'strlen');
            $ctt = '';
            foreach ($s as $a) {
                $ctt .= $list_sub_transport[$a];
            }
            $array['type_sub_transport'] = $ctt;
        }
        return $array;
    }
}
