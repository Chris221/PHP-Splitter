# PHP File Splitter
A PHP File Splitter to split larger files into smaller equal ones. This splitter is a binary safe and can handle large files well over 100GB.

### How to use
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