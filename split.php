<?php
//sets a higher memory limit
ini_set('memory_limit', '1G');
//clears the terminal
system('clear');
//changes the terminal color to white
echo("\033[1;37m");

//loops through getting the file to split
while (@$confirm_file != "y") {
  //asks what file you want to split
  echo("What file would you like to split?\n");
  //gets the input from the terminal and removes the new line for the file name
  $filename = trim(fgets(STDIN), "\n");
  //asks to confirm file
  echo("You want to split this file \"$filename\"? [y or n] ");
  //gets the input from the terminal and removes the new line for the file name conformation
  $confirm_file = trim(fgets(STDIN), "\n");
  //breaks the terminal line
  echo("\n");
}

//looks for the path, name, & extention of the file we are splitting
if (preg_match("{^([\S]*(\\/|\\\))*(?:([\S]+)(\\.[\S]+)|([\S]+))$}",$filename,$fm)) {
  //sets either the path or nothing
  $path = isset($fm[1]) ? $fm[1] : "";
  //sets the name of the file from either the 3rd match if there is an extention or from the 5th match if not
  $name = isset($fm[3]) ? $fm[3] : $fm[5];
  //sets either the extention or nothing
  $ext = isset($fm[4]) ? $fm[4] : "";
  //dies due to unable to parse filename
} else die("ERROR: Could not match \"$filename\" to an path, name, & extention...\n");

//loops through getting the way you want to split the file
while (@$confirm_option != "y") {
  //asks what you want to split by
  echo("Would you like to split by size (s) or lines (l)? [Default: s] ");
  //gets the input from the terminal and removes the new line for what to split by
  $split_by = trim(fgets(STDIN), "\n");
  //parses out the actual words for split_by
  $split_by = $split_by == "l" ? "lines" : "size";
  //asks to confirm split method
  echo("You want to split by $split_by? [y or n] ");
  //gets the input from the terminal and removes the new line for the split method conformation
  $confirm_option = trim(fgets(STDIN), "\n");
  //breaks the terminal line
  echo("\n");
}

//if splitting by lines
if ($split_by == "lines") {
  //loops through getting the lines to split by
  while (@$confirm_lines != "y") {
    //asks how many lines to split by
    echo("How many lines would you like to split by? [Default: " . number_format(100000) . "] ");
    //gets the input from the terminal and removes the new line for the number of lines to split by
    $number_of_lines = trim(fgets(STDIN), "\n") ?: 100000;
    //asks to confirm the number of lines
    echo("You want to split by " . number_format($number_of_lines) . " lines? [y or n] ");
    //gets the input from the terminal and removes the new line for the number of lines conformation
    $confirm_lines = trim(fgets(STDIN), "\n");
    //breaks the terminal line
    echo("\n");
  }
  //sets size to false since in lines
  $size = false;
  
  //outputs that it is getting the line number
  echo("Getting number of lines...\033[1;37m\n");
  //outputs that it may take a while on large files
  echo("Depending on file size this may take some time...\033[1;37m\n");
  //opens the file
  $f = @fopen($filename, 'rb');
  //defines the total_line var
  $total_lines = 0;
  //counts through the file to get all the new lines
  while (!feof($f)) $total_lines += substr_count(fread($f, 8192), "\n");
  //closes the main file
  fclose($f);
  //outputs the number of total lines
  echo("$total_lines number of lines..\033[1;37m\n");

  //gets the estimated number of files from dividing the number of lines
  $number_of_files = ceil(@($total_lines / $number_of_lines));
//otherwise splitting by size
} else if ($split_by == "size") {
  //loops through getting the size to split by
  while (@$confirm_size != "y") {
    //asks what size to split by
    echo("How big would like to split the files? [Default: 3mb] ");
    //gets the input from the terminal and removes the new line for the size to split by
    $file_size = trim(fgets(STDIN), "\n") ?: "3mb";
    //asks to confirm the size
    echo("You want to split the files with a size of $file_size? [y or n] ");
    //gets the input from the terminal and removes the new line for the size conformation
    $confirm_size = trim(fgets(STDIN), "\n");
    //breaks the terminal line
    echo("\n");
  }
  //sets size to false since in size
  $size = true;
  //looks for the shorthand size in the text then makes the file_size to split on
  if (preg_match("{^(\d+)(b|kb|mb|gb|tb)?$}i",strtolower($file_size),$m)) {
    //if kilobytes
    if (@$m[2] == "kb") $multiplier = 1024;
    //if megabytes
    else if (@$m[2] == "mb") $multiplier = 1024*1024;
    //if gigabytes
    else if (@$m[2] == "gb") $multiplier = 1024*1024*1024;
    //if terabytes
    else if (@$m[2] == "tb") $multiplier = 1024*1024*1024*1024;
    //if bytes or not in shorthand
    else $multiplier = 1;
    //figures out the file size
    $file_size = $m[1] * $multiplier;
    //dies due to unable to parse size
  } else die("ERROR: Could not match $file_size to an actual size...\n");

  //gets the estimated number of files from dividing the size
  $number_of_files = ceil(@(filesize($filename) / $file_size));
}

//outputs the estimated number of files after the split
echo("Esitmated number of files: $number_of_files \n\n");

//defines the number for the name
$i = 0;

//opens the main file
$file = fopen($filename, "rb");
if (!file_exists("split/")) mkdir("split/",0777,true);

echo("Splitting $filename...\033[1;37m\n");

//while the main file hasn't ended loop
while (!feof($file)) {
  //increments the loop for filenames
  @$i++;
  
  //makes the new file name
  $new_file = "split/$name.$i$ext";
  //opens the new file
  $new = fopen($new_file,"ab");
  //if splitting on size
  if ($size) {
    //while the new file's size is less then the file size we are splitting on & the main file hasn't ended loop
    while (filesize($new_file) < ($file_size) && !feof($file)) {
      //clears the stat cache so filesize() will update
      clearstatcache();
      //gets the next line
      $line = fgets($file);
      //writes the next line to the new file
      fwrite($new, $line);
    }
  //otherwise splitting on lines
  } else {
    //start a line counter
    $cl = 0;
    //while the line counter is less then the number of lines & the main file hasn't ended loop
    while($cl < $number_of_lines && !feof($file)) {
      //gets the next line
      $line = fgets($file);
      //writes the next line to the new file
      fwrite($new, $line);
      //increases the new file's line count
      $cl++;
    }
  }
  //closes the new file
  fclose($new);
  //outputs file has been completed
  echo("File $i / $number_of_files \033[1;32mCompleted\033[1;37m!\n");
}
//outputs splitting is completed
echo("\nSplitting of $filename \033[1;32mCompleted\033[1;37m!\n\n");
//closes the file
fclose($file);
?>