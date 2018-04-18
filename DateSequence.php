<?php
/*
# DateSequence.php ver.0.2.0  2018.4.18  (c)Takeru.
#
# 指定年月のDatetimeクラスの拡張日付セット配列を生成
# 日付セットは指定年月の前後を含むことがあり、全部で42日（６週分）の配列
# getDate($index)で指定インデックスのDatetimeXクラスを返す
# getDate('curr')は指定年月（実行日ではない）初日のDatetimeXクラスを返す
# getDate('prev')は指定年月前月の初日のDatetimeXクラスを返す
# getDate('next')は指定年月翌月の初日のDatetimeXクラスを返す
#   DatetimeXクラスにはstatus[]配列が追加されている
#   status['range'] : 当該月の日付 = 0, 前月の日付 = -1, 翌月の日付 = 1
#   status['today'] : 実行日当日 = 1, それ以外 = 0
#   status['holiday'] : 休日 = 1, 平日 = 0（現在メソッド未実装）
#   これらの値はDatetimeXクラスに対してgetStatus()メソッドで取得できる
#
#        Copyright (c) 2018 Takeru.
#        Release under the MIT license
#        http://opensource.org/licenses/MIT
#
date_default_timezone_set('Asia/Tokyo');
$now = new Datetime();
$year = $now->format('Y');
$month = $now->format('n');
$diff = $now->format('j') + (new Datetime($year.'-'.$month.'-1'))->format('w') - 1;
$dt = new DateSequence($year,$month);
var_dump($dt->getDate($diff)->getStatus());
var_dump($dt->getDate('curr')->format('Ym'));
var_dump($dt->getDate('prev')->format('Ym'));
var_dump($dt->getDate('next')->format('Ym'));
var_dump($dt->getDate(0)->getStatus());
*/

class Datetimex extends Datetime {
  private $status;
  function setRange($status){
    $this->status['range'] = $status;
  }
  function setToday($status){
    $this->status['today'] = $status;
  }
  function setHoliday($status){
    $this->status['holiday'] = $status;
  }
  function getStatus() {
    return $this->status;
  }
}
class DateSequence {
  private $dateTime;
  private $dateSequence;
  function __construct($year = 0, $month = 0) {
    $this->dateTime['now'] = (new Datetimex())->setTime(0,0);
    $year = ($year > 1900 && $year < 10000) ? $year : $this->dateTime['now']->format('Y');
    $month = ($month > 0 && $month < 13) ? $month : $this->dateTime['now']->format('n');
    $this->dateTime['curr'] = new Datetimex($year."-".$month."-1");
    $date = (clone $this->dateTime['curr'])->modify('-1 days');
    $this->dateTime['prev'] = new Datetimex($date->format('Y')."-".$date->format('n')."-1");
    $date = $date->modify('32 days');
    $this->dateTime['next'] = new Datetimex($date->format('Y')."-".$date->format('n')."-1");

    $date = clone $this->dateTime['curr'];
    $this->dateSequence[] = $date->modify('-'.(int)$date->format('w').' days');
    for ($i=1;$i<42;$i++) {
      $this->dateSequence[$i] = (clone $this->dateSequence[$i - 1])->modify('1 days');
    }
    for ($i=0;$i<42;$i++) {
      $this->dateSequence[$i]->setRange((int)$this->dateSequence[$i]->format('n') - (int)$this->dateTime['curr']->format('n'));
      if ($this->dateSequence[$i] == $this->dateTime['now']) {
        $this->dateSequence[$i]->setToday(1);
      } else {
        $this->dateSequence[$i]->setToday(0);
      }
/*
      if ($this->dateSequence[$i]->format('w') == 1) {
        $this->dateSequence[$i]->setHoliday(1);
      }
*/
    }
  }
  function getDate($index) {
    if (is_int($index) && ($index >= 0 && $index <= 41)) {
      return $this->dateSequence[$index];
    } elseif ($index == 'curr' || $index == 'prev' || $index == 'next') {
      return $this->dateTime[$index];
    } else {
      return null;
    }
  }
}
