<?php
// Функция конвертации числа байт в человеческий вид

function file_size($size)  
{
    $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");  
    return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';  
}

function showDate($date) // $date --> время в формате Unix time
{
    $stf = 0;
    $cur_time = time();
    $diff = $cur_time - $date;
 
    $seconds = array('секунда', 'секунды', 'секунд');
    $minutes = array('минута', 'минуты', 'минут');
    $hours = array('час', 'часа', 'часов');
    $days = array('день', 'дня', 'дней');
    $weeks = array('неделя', 'недели', 'недель');
    $months = array('месяц', 'месяца', 'месяцев');
    $years = array('год', 'года', 'лет');
    $decades = array('десятилетие', 'десятилетия', 'десятилетий');
 
    $phrase = array($seconds, $minutes, $hours, $days, $weeks, $months, $years, $decades);
    $length = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
 
    for ($i = sizeof($length) - 1; ($i >= 0) && (($no = $diff / $length[$i]) <= 1); $i--) ;
    if ($i < 0) $i = 0;
    $_time = $cur_time - ($diff % $length[$i]);
    $no = floor($no);
    $value = sprintf("%d %s ", $no, getPhrase($no, $phrase[$i]));
 
    if (($stf == 1) && ($i >= 1) && (($cur_time - $_time) > 0)) $value .= time_ago($_time);
 
    return $value . ' назад';
}
function getPhrase($number, $titles)
{
    $cases = array (2, 0, 1, 1, 1, 2);
    return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
}

// Строка системного запроса подключения к управляющему порту OpenVPN
$string="(echo status 2; sleep 0.1; echo quit ) | nc 127.0.0.1 7505 | grep ^CLIENT_LIST|sort -t 2";
exec($string, $ovpn);

echo "<table border='1'>";
echo "<tr><th ALIGN=left>Имя клиента</th><th>Внешний адрес</th><th>Внутренний адрес</th><th>От клиента</th><th>К клиенту</th><th>Соединение установлено</th>";
foreach ($ovpn as $x) 
{
$row = explode(",", $x);

$from_addr = explode(":", $row[2]);


          echo "<tr>" .
               "<td>" . $row[1] .
               "<td><a href=http://http://1whois.ru/?url=" . $from_addr[0] .">".$from_addr[0]."</a>" .
               "<td><a href=http://" . $row[3] .">".$row[3]."</a>" .
               "<td>" . file_size($row[4]) .
               "<td>" . file_size($row[5]) .
               "<td>" . date('d.m.Y H:i:s',strtotime($row[6])) ." (". showDate(strtotime($row[6])) .")".
               "</td>";



}
echo "</tr></table>";



?>
