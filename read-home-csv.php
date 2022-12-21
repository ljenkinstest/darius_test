<?php

// $kpi = $_GET['kpi'];

function utf8ize($mixed) {
  if (is_array($mixed)) {
      foreach ($mixed as $key => $value) {
          $mixed[$key] = utf8ize($value);
      }
  } else if (is_string ($mixed)) {
      return utf8_encode($mixed);
  }
  return $mixed;
}

function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
  $encoded = json_encode($value, $options, $depth);
  switch (json_last_error()) {
      case JSON_ERROR_NONE:
          return $encoded;
      case JSON_ERROR_DEPTH:
          return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_STATE_MISMATCH:
          return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_CTRL_CHAR:
          return 'Unexpected control character found';
      case JSON_ERROR_SYNTAX:
          return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_UTF8:
          $clean = utf8ize($value);
          if ($utfErrorFlag) {
              return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
          }
          return safe_json_encode($clean, $options, $depth, true);
      default:
          return 'Unknown error'; // or trigger_error() or throw new Exception()

  }
}


function extractFile($filename) {
	$file = fopen($filename, "r");
	while (!feof($file)) {
		$result[] = (fgetcsv($file));
	}
	return safe_json_encode($result);
	fclose($file);
}



print_r(extractFile("home-csv/home.csv"));