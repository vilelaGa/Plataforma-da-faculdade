<?php

namespace App\ValidaData;

class ValidaData
{
    public static function ValidaData($dataIni, $dataFin)
    {
        // Checka se a data e valida
        $explodeDataInicio = explode('-', $dataIni);
        global $checkDataIni;
        $checkDataIni = checkdate($explodeDataInicio[1], $explodeDataInicio[2], $explodeDataInicio[0]);

        $explodeDataFin = explode('-', $dataFin);
        global $checkDataFin;
        $checkDataFin = checkdate($explodeDataFin[1], $explodeDataFin[2], $explodeDataFin[0]);

        // Da o resultado para analisar se a data inicial e maior que a final
        $dataStrIni = $explodeDataInicio[0] . '-' . $explodeDataInicio[1] . '-' . $explodeDataInicio[2];
        global $dataTimeIni;
        $dataTimeIni = strtotime($dataStrIni);

        $dataStrFin = $explodeDataFin[0] . '-' . $explodeDataFin[1] . '-' . $explodeDataFin[2];
        global $dataTimeFin;
        $dataTimeFin = strtotime($dataStrFin);
    }
}
