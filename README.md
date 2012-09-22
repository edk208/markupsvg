markupsvg
=========

Uncommented Markup SVG implementation
This code has not been optimized or commented.  I hope to get around to cleaning it up soon.

Examples

enter the URL/index_snake.php?im=1.jpg
I included images 1 - 241


Known issues
1. You cannot store the results 
	I had a mysql database storing results, but I disabled authentication in dbauth.php
	You can create a database, enter your credentials in dbauth to enable storage

2. You cannot use the active contour server side script
	I created a matlab cgi script that takes the manual points and evolves them using
	a snakes implementation.  For this to work, you need matlab and the cgi script on 
	your server.  For the canned examples, you can hit my server to see how it works
	by uncommenting two lines in snakeCompute.php...

