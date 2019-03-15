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


echo("[" . current_time() . "] Getting files in $folder...\033[1;37m\n\n");

$local_files = scandir($folder,SCANDIR_SORT_ASCENDING);

sort($local_files, SORT_NATURAL);
$files = [];

foreach($local_files as $file) {
  if ($file == '.' || $file == '..') continue;
  $files[] = $file;
}

$number_of_files = count($files);

echo("[" . current_time() . "] Number of files to combine " . number_format($number_of_files) . " ...\033[1;37m\n");
echo("[" . current_time() . "] Getting the number of lines.. This may take some time ...\033[1;37m\n\n");

$number_of_lines = 0;
$count = 1;
foreach($files as $f) {
  $num_lines = getLines($folder . $f);
  echo("[" . current_time() . "] \033[1;32m" . number_format($count) . "\033[1;37m/\033[1;32m" . number_format($number_of_files) . "\033[1;37m $f: " . number_format($num_lines) . " lines\033[1;37m\n");
  $number_of_lines += $num_lines;
  $count++;
}

echo("[" . current_time() . "] Number of lines to combine " . number_format($number_of_lines) . " ...\033[1;37m\n\n");

$i = 1;

while (file_exists("combined.$i.txt")) $i++;

$name = "combined.$i.txt";


echo("[" . current_time() . "] Combining \"$folder\" into \"$name\"...\033[1;37m\n\n");
$combine = fopen($name,"ab");

foreach($files as $f) {
  @$current_file_number++;
  $file = fopen($folder . $f,"rb");
  while(!feof($file)) {
    $line = fgets($file);
    if (strlen($line) == 0) continue;
    fwrite($combine, $line);
  }

  echo("[" . current_time() . "] File " . number_format($current_file_number) . " / "
    . number_format($number_of_files) . " \033[1;32mDone\033[1;37m!\n");
  fclose($file);
}
fclose($combine);
//breaks the terminal line
echo("\n");
//outputs splitting is completed
echo("[" . current_time() . "] Combining of $folder \033[1;32mCompleted\033[1;37m!\n\n");


?>