<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caop;
use DB;

class CaopController extends Controller
{

    public function getDicofre($coords)
    {
        $coordArray = explode(",", $coords);

        $data["dicofre"] = Caop::select(
            DB::raw('dicofre','distrito'))
        ->whereRaw( 'ST_Contains(SHAPE, ST_PointFromText("POINT(' . $coordArray[0] . ' ' . $coordArray[1] . ')"))' )
        ->firstOrFail();

        $data["distritos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 2) AS dicofre_calc"),'distrito')
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 2)'))
            ->orderBy('distrito', 'asc')
            ->pluck('distrito', 'dicofre_calc')
            ->toArray();

        $data["concelhos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 4) AS dicofre_calc"),'concelho')
            ->whereRaw('SUBSTRING(dicofre, 1, 2) = ' . substr($data["dicofre"]->dicofre,0,2))
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 4)'))
            ->orderBy('concelho', 'asc')
            ->pluck('concelho', 'dicofre_calc')
            ->toArray();

        $data["freguesias"] = Caop::orderBy('freguesia', 'asc')
            ->whereRaw('SUBSTRING(dicofre, 1, 4) = ' . substr($data["dicofre"]->dicofre,0,4))
            ->groupBy('freguesia')
            ->pluck('freguesia', 'dicofre')
            ->toArray();

        return $data;
    }

    public function getConcelhos($distrito)
    {

        $data["concelhos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 4) AS dicofre_calc"),'concelho')
            ->whereRaw('SUBSTRING(dicofre, 1, 2) = ' . $distrito)
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 4)'))
            ->orderBy('concelho', 'asc')
            ->pluck('concelho', 'dicofre_calc')
            ->toArray();

        return $data;
    }

    public function getFreguesias($concelho)
    {

        $data["freguesias"] = Caop::whereRaw('SUBSTRING(dicofre, 1, 4) = ' . $concelho)
            ->orderBy('freguesia', 'asc')
            ->pluck('freguesia', 'dicofre')
            ->toArray();

        return $data;
    }

}