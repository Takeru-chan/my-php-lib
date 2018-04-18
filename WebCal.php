<!doctype html>
<!--
# WebCal.php ver.1.0.0  2018.4.19  (c)Takeru.
# DateSequence.php ライブラリ利用サンプル
# ライブラリファイルは同一ディレクトリに配置のこと
#
#        Copyright (c) 2018 Takeru.
#        Release under the MIT license
#        http://opensource.org/licenses/MIT
#
-->
<html>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
<?php
$width = 500;   // カレンダー幅（px）を書き換えると高さも自動調整されます。
$heighth = $width / 20;
$heightd = $width / 10;
$style = <<< STYLE
  <style>
  body {width:{$width}px;padding:1em;margin:auto;}
  .flex {display:flex;justify-content:space-around;}
  .flex a {text-decoration:none;color:blue;}
  table {width:100%;table-layout:fixed;border-collapse:collapse;}
  td.nocurr {background:lightgray;color:white;}
  td#today {background:lightgreen;color:white;}
  td.holiday {background:pink;}
  td#today.holiday {background:pink;color:red;}
  th {height:{$heighth}px;border:solid 1px #aaa;background:lightblue;}
  td {height:{$heightd}px;border:solid 1px #aaa;text-align:left;vertical-align:top;}
  </style>
STYLE;
echo $style;
?>
</head>
<body>
<?php
date_default_timezone_set('Asia/Tokyo');
include_once('./DateSequence.php');
$now = new Datetime;
$year = ($_GET['year'] != '') ? $_GET['year'] : $now->format('Y');
$month = ($_GET['month'] != '') ? $_GET['month'] : $now->format('n');
$week = $now->format('W');
$cal = new DateSequence($year,$month);
echo "<div class='flex'><p><a href='?year=".$cal->getDate('prev')->format('Y')."&month=".$cal->getDate('prev')->format('n')."'>&laquo;&nbsp;Prev</a></p>";
echo "<p>".$cal->getDate('curr')->format('F')." ".$cal->getDate('curr')->format('Y')."</p>";
echo "<p><a href='?year=".$cal->getDate('next')->format('Y')."&month=".$cal->getDate('next')->format('n')."'>Next&nbsp;&raquo;</a></p></div>";
echo "<table>\n";
echo "<tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr>\n";
for ($i=0;$i<6;$i++) {
  echo "<tr>";
  for ($j=0;$j<7;$j++) {
    $status = ($cal->getDate($j + 7 * $i))->getStatus();
    if ($status['range'] != 0) {
      echo "<td class='nocurr'>";
    } elseif ($status['today'] == 1) {
      echo "<td id='today'>";
    } else {
      echo "<td>";
    }
    echo $cal->getDate($j + 7 * $i)->format('j')."</td>";
  }
  echo "</tr>\n";
  }
echo "</table>\n";
echo "<p class='flex'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."'>&#91;&nbsp;Current month&nbsp;&#93;</a></p>";
?>
</body>
</html>
