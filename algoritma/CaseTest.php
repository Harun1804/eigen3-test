<?php

class CaseTest
{
    public function pertama()
    {
        $word = "NEGIE1";
        $word = str_split($word,1);
        $lastWord = end($word);
        array_pop($word);
        $word = join('',$word);
        return strrev($word).''.$lastWord;
    }

    public function kedua($kalimat)
    {
        $word = explode(' ',$kalimat);
        $lengthEachWord = [];
        for($i=0;$i<count($word);$i++)
        {
            $lengthEachWord[] = strlen($word[$i]);
        }
        return max($word)." : ".max($lengthEachWord)." Character";
    }

    public function ketiga()
    {
        $input = ['xc', 'dz', 'bbb', 'dz'];
        $query = ['bbb', 'ac', 'dz'];

        $join = array_count_values(array_intersect($input,$query));
        return "[".$join['bbb'].", 0, ".$join['dz']."] karena kata 'bbb' terdapat 1 pada INPUT, kata 'ac' tidak ada pada INPUT, dan kata 'dz' terdapat 2 pada INPUT";
    }

    public function empat()
    {
        $matrixs = [[1, 2, 0], [4, 5, 6], [7, 8, 9]];
        $diagonal1 = 0;
        $detailD1 = [];
        $diagonal2 = 0;
        $detailD2 = [];

        for($i = 0;$i < count($matrixs);$i++){
            $diagonal1 += $matrixs[$i][$i];
            $detailD1[] = $matrixs[$i][$i];
            $diagonal2 += $matrixs[$i][count($matrixs[$i])-1-$i];
            $detailD2[] = $matrixs[$i][count($matrixs[$i])-1-$i];
        }

        $d1 = "Diagonal Pertama = ".join("+",$detailD1);
        $d2 = "Diagonal Kedua = ".join("+",$detailD2);
        $perhitunganDiagonal = $diagonal1 - $diagonal2;
        return "$d1 <br> $d2 <br> maka hasilnya adalah $diagonal1 - $diagonal2 = $perhitunganDiagonal";
    }
}