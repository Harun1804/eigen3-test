<?php

include_once 'CaseTest.php';

$fungsi = new CaseTest();
print_r("1. ".$fungsi->pertama());
echo "<br>";
print_r("2. ".$fungsi->kedua("Aku Ganteng Fix"));
echo "<br>";
print_r("3. ".$fungsi->ketiga());
echo "<br>";
print_r("4. ".$fungsi->empat());