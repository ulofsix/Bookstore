<?php

$file = "webdict.txt";


$myfile = fopen($file, "r");

while($f_row = fgets($myfile)){
    echo $f_row;
    echo "<br>";
}
echo("<br>");
fseek($myfile,0);

while($f_row = fgetc($myfile)){
    echo $f_row;
    echo "<br>";
}
