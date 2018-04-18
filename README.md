# My personal PHP libary
簡単なPHPのライブラリ集です。  

## LineData.php
指定されたテキストファイルを読み込んで、一行ごとの配列を返すクラス。  

- getStatus() - ファイル読み込み成否を返す。失敗はfalse  
- getLines() - 空行および行頭#行を除いた１行１データの配列を返す  
- getAllLines() - 空行等を含む全ての行を１行１データの配列で返す  

## DateSequence.php
指定年月のDatetimeクラス拡張日付セットの配列を返すクラス。  

一ヶ月分のカレンダーを生成する場合等、当該年月を含む42日（６週）分の日付情報を取得するのに使う。
DatetimeXクラスとともに使用して、前月/翌月や当日か否か、休日か否かの付加情報を得る。  

- getDate($index) - 指定インデックスのDatetimeXクラスを返す（0<=$index<=41）  
- getDate('curr') - クラス生成指定年月（実行日ではない）初日のDatetimeXクラスを返す  
- getDate('prev') - クラス生成指定年月前月の初日のDatetimeXクラスを返す  
- getDate('next') - クラス生成指定年月翌月の初日のDatetimeXクラスを返す  

アプリケーションサンプル：WebCal.php  

## DatetimeX
DateSequenceクラス用のDatetime拡張クラス。  

- getStaus() - DatetimeXクラスの付加情報を返す  
  status['range'] : 指定年月の日付 = 0, 指定年月前月の日付 = -1, 指定年月翌月の日付 = 1  
  status['today'] : 実行日当日 = 1, それ以外 = 0  
  status['holiday] : 休日 = 1, 平日 = 0（メソッド未実装）  

## License
This script has released under the MIT license.  
[http://opensource.org/licenses/MIT](http://opensource.org/licenses/MIT)
