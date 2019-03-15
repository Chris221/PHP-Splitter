# PHP File Splitter
A PHP File Splitter to split larger files into smaller equal ones. THIS SPLITTER NEVER SPLITS MID-LINE. This splitter is a binary safe and can handle large files well over 100GB. It has also been tested splitting and recombining many types of files. There is also a Combine script that will take your split file and combine it back again correct.

### How to use the PHP File Splitter
To run the use the php splitter from a command line run

php split.php

It will then ask you what file you like to split.
Enter the path to the file from the root directory of split.
It will then ask you to confirm the file with either "y" or "n".

Next it will ask if you want to split by file size (s) vs number of lines (l) in each file.
If you do not enter anything it will default to file size.
Confirm with either "y" or "n".

Now it will either ask for: the number of lines you want in each file, or the file size for each file.
If you choose size then you can: enter raw numbers and that will be the size, or you can use (b|kb|mb|gb|tb) after a number to let the splitter figure out the size.
The default for size is 3MB.

If you choose lines then you can enter the number of lines you want in each file.
The default for lines is 100,000.

After entering into line mode the spitter will begin to figure out the total lines, this may take some time.


### How to use the PHP File Combiner
To run the use the php combiner from a command line run

php combine.php

It will then ask you what folder you like to combine.
Enter the path to the folder from the root directory of combine.
It will figure out if you need the trailing /.
It will then ask you to confirm the folder with either "y" or "n".

If the folder is not there it will die otherwise it will being combining the file back together.