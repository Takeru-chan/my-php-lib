# My personal PHP libary
簡単なPHPのライブラリ集です。  

## LineData.php
指定されたテキストファイルを読み込んで、一行ごとの配列を返すクラス。  

- getStatus() - ファイル読み込み成否を返す。失敗はfalse  
- getLines() - 空行および行頭#行を除いた１行１データの配列を返す  
- getAllLines() - 空行等を含む全ての行を１行１データの配列で返す  

## License
This script has released under the MIT license.  
[http://opensource.org/licenses/MIT](http://opensource.org/licenses/MIT)
