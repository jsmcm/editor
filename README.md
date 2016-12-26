# editor
an online file management and text editor system for websites written in PHP and js

This is a sub project of the WebCP (http://webcp.pw) Web Hosting Control Panel project. 

NOTES:
--------
1) This project is a number of years old and as such may not be up to modern coding standards. This should be changed


Installation:\r\n
------------------
To install simply clone or download this into the directory you'd want to access the editor from. For instance, if your website use public_html, you can place this in public_html and then access the editor via www.example.com/editor

Configuration:\r\n
-------------------
There should be a config-sample.inc.php file in the top most directory. You can copy this to config.inc.php and update your DocumentRoot, email address and password.

	- Email Address / Password. These are the login details you will use to authenticate with. At present it uses a very simple, single user authentication. This may be changed in the future to allow multiple logins

	- DocumentRoot. This is the root of what you want to edit. For instance you want to edit everything in your public_html, and it is at /home/user/public_html/ then put that as your document root. If you only want to edit a sub directory called subdir, then put /home/user/public_html/subdir. You do need to ensure that the permissions are correct.


