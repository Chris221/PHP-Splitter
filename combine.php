<?php
//sets a higher memory limit
ini_set('memory_limit', '1G');
//clears the terminal
system('clear');
//changes the terminal color to white
echo("\033[1;37m");


$folder = "BTC Collection/";



//sets timezone
date_default_timezone_set("America/New_York");
//gets the current time for outputting
function current_time() {
  // Get the current time
  return date("m/d/Y h:i:s a", time());
}

function getLines($file) {
  $f = @fopen($file, 'rb');
  if (!$f) return 0;
  $lines = 0;
  while (!feof($f)) $lines += substr_count(fread($f, 8192), "\n");
  fclose($f);
  return $lines;
}


echo("[\033[0;37m".current_time()."\033[1;37m] Getting files in $folder...\033[1;37m\n\n");

$local_files = scandir($folder,SCANDIR_SORT_ASCENDING);

sort($local_files, SORT_NATURAL);
$files = [];

foreach($local_files as $file) {
  if ($file == '.' || $file == '..') continue;
  preg_match("{^(.+)(?=\.part).part\d+$}",$file,$n);
  if (isset($n[1])) {
    $name = $n[1];
    $files[] = $file;
  } else continue;
}

$number_of_files = count($files);

echo("[\033[0;37m".current_time()."\033[1;37m] Number of files to combine " . number_format($number_of_files) . " ...\033[1;37m\n");
echo("[\033[0;37m".current_time()."\033[1;37m] Getting the number of lines.. This may take some time ...\033[1;37m\n\n");

$number_of_lines = 0;
$count = 1;
foreach($files as $f) {
  $num_lines = getLines($folder . $f);
  echo("[\033[0;37m".current_time()."\033[1;37m] \033[1;32m" . number_format($count) . "\033[1;37m/\033[1;32m" . number_format($number_of_files) . "\033[1;37m $f: " . number_format($num_lines) . " lines\033[1;37m\n");
  $number_of_lines += $num_lines;
  $count++;
}

echo("[\033[0;37m".current_time()."\033[1;37m] Number of lines to combine " . number_format($number_of_lines) . " ...\033[1;37m\n\n");


echo("[\033[0;37m".current_time()."\033[1;37m] Combining \"$folder\" into \"$name\"...\033[1;37m\n\n");
$combine = fopen($name,"wb");

foreach($files as $f) {
  @$current_file_number++;
  $file = fopen($folder . $f,"rb");
  while(!feof($file)) {
    $line = fgets($file);
    if (strlen($line) == 0) continue;
    fwrite($combine, $line);
  }

  echo("[\033[0;37m".current_time()."\033[1;37m] File " . number_format($current_file_number) . " / "
    . number_format($number_of_files) . " \033[1;32mDone\033[1;37m!\n");
  fclose($file);
}
fclose($combine);
//breaks the terminal line
echo("\n");
//outputs splitting is completed
echo("[\033[0;37m".current_time()."\033[1;37m] Combining of $folder \033[1;32mCompleted\033[1;37m!\n\n");


?>