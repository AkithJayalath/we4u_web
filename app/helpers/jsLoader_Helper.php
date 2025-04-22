<?php 
function loadJs($jssheets){
  $jsString = '';
  foreach ($jssheets as $jssheet){
    $jsPath = URLROOT.'/js/'.$jssheet.'js';
    $jsString .= '<script src="$jsPath"></script>';
  }
  return $jsString;
};

?>
