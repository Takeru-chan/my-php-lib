<?php
/*
# DateSequence.php ver.0.3.0  2018.4.19  (c)Takeru.
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
#   status['holiday'] : 休日 = 1, 平日 = 0
#   これらの値はDatetimeXクラスに対してgetStatus()メソッドで取得できる
# setHoliday($array)で指定日のDatetimeXクラスのstatus['holiday']をセット/リセットする
#   インデックスが正の整数：指定日を休日にセットする
#   インデックスが負の整数：指定日を平日にセットする
#   インデックスがw+整数：指定曜日を休日にセットする（0=日曜、1=月曜、…、6=土曜）
#   インデックスがs+整数：日曜の指定週を休日にセットする
#   インデックスがm+整数：月曜の指定週を休日にセットする
#   インデックスがt+整数：火曜の指定週を休日にセットする
#   インデックスがd+整数：水曜の指定週を休日にセットする
#   インデックスがh+整数：木曜の指定週を休日にセットする
#   インデックスがf+整数：金曜の指定週を休日にセットする
#   インデックスがa+整数：土曜の指定週を休日にセットする
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
    }
  }
  function getDate($index) {
    if (is_int($index)) {
      return $this->dateSequence[$index];
    } elseif ($index == 'curr' || $index == 'prev' || $index == 'next') {
      return $this->dateTime[$index];
    } else {
      return null;
    }
  }
  function setHoliday($array) {
    foreach ($array as $value) {
      if (!(int)$value) {
        $initial = substr($value, 0, 1);
        $final = (int)substr($value, 1);
        switch ($initial) {
        case 'w':
          if ($final >= 0 && $final <= 6) {
            $week = $final;
            self::setHolidayWeek($week);
          }
          break;
        case 's':
        case 'm':
        case 't':
        case 'd':
        case 'h':
        case 'f':
        case 'a':
          if ($final > 0 && $final < 6) {
            $week_seq = 'smtdhfa';
            $position = (int)strpos($week_seq, $initial);
            if ($this->dateSequence[1]->getStatus()['range'] == 0) {
              $this->dateSequence[($final - 1) * 7 + $position]->setHoliday(1);
            } else {
              $this->dateSequence[$final * 7 + $position]->setHoliday(1);
            }
          }
          break;
        }
      } elseif ((int)$value && $value > 0 && $value < 32) {
        $diff = $this->dateTime['curr']->format('w') - 1;
        $this->dateSequence[$diff + $value]->setHoliday(1);
      } elseif ((int)$value && $value < 0 && $value > -32) {
        $diff = $this->dateTime['curr']->format('w') - 1;
        $this->dateSequence[$diff - $value]->setHoliday(0);
        // Cancellation decision of holiday is the last!
      }
    }
  }
  function setHolidayWeek($week) {
    if ($week >= 0 && $week <= 6) {
      for ($i=0;$i<6;$i++) {
        $index = $week + $i * 7;
        if ($this->dateSequence[$index]->getStatus()['range'] == 0) {
          $this->dateSequence[$index]->setHoliday(1);
        }
      }
    }
  }
}
