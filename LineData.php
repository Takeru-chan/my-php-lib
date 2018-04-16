<?php
/*
# LineData.php ver.0.1.0  2018.4.17  (c)Takeru.
#
# 指定ファイルの各行の内容を配列で返す
# getStatus()で読込成否を確認。失敗：false
# getLines()は空行および行頭#を読み飛ばして各行を配列で返す
# getAllLines()で空行を含む全ての行を配列で返す
#
#        Copyright (c) 2018 Takeru.
#        Release under the MIT license
#        http://opensource.org/licenses/MIT
#
$filename = basename($_SERVER["PHP_SELF"]);
$ld = new LineData($filename);
if ($ld->getStatus()) {
  echo "Specified file is exist.\n";
  echo "Total lines : ".count($ld->getAllLines())."\n\n";
  echo "Comments and blanks excluded as below.\n";
  $ln = $ld->getLines();
  foreach ($ln as $value) {
    echo $value."\n";
  }
} else {
  echo "Specified file is NOT exist.\n";
}
*/

class LineData {
  private $status;
  private $alllines;
  private $lines;
  function __construct($filename) {
    if ($this->status = file_exists($filename)) {
      $this->alllines = file($filename, FILE_IGNORE_NEW_LINES);
    }
  }
  function getStatus() {
    return $this->status;
  }
  function getLines() {
    foreach ($this->alllines as $value) {
      if (!preg_match('/^$|^#/',$value)) {
        $this->lines[] = $value;
      }
    }
    return $this->lines;
  }
  function getAllLines() {
    return $this->alllines;
  }
}
