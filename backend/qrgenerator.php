<?php
include('./lib/phpqrcode/qrlib.php');

if (isset($_GET["code"])) {
    $code = $_GET["code"];
    $dataText   = "hi";
  $svgTagId   = 'id-of-svg';
  $saveToFile = false;
  $imageWidth = 250; // px

  // SVG file format support
  $svgCode = QRcode::svg($dataText, $svgTagId, $saveToFile, QR_ECLEVEL_L, $imageWidth);
  echo $svgCode;
}
?>
