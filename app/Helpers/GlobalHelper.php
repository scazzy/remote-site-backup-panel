<?php
/**
 * Global Helper Methods
 */

// Data dumper
function magic($data, $exit= false) {
 echo "<pre>";
 print_r($data);
 echo "</pre>";
 if($exit) {
   exit;
 }
}

function returnJson(Array $arr) {
  return Response::json($arr);
}
