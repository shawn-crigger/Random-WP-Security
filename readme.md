# Random Wordpress Security Thoughts

Since I just got done re-builing 2 wordpress websites, that I got hacked and after they were hacked I upgraded and secured
6 more in the week afterwards before they got hacked, This is a growing collection of simple things I add to all my wordpress
installs to help with securing wordpress.


### Files included and what to do with them.

 - [.htaccess](https://github.com/DreadyCrig/Random-WP-Security/blob/master/.htaccess)

Common Spammer IP Blacklists, Spam User Agent Blacklists, and a few other random security and performance enchantments.

This **.htaccess** is a combination of [](http://perishablepress.com/5g-blacklist-2013/) and [HTML5BP](https://github.com/h5bp/html5-boilerplate/blob/master/dist/.htaccess)


 - [wp-config.php](https://github.com/DreadyCrig/Random-WP-Security/blob/master/wp-config-debug.php)

 Just some helpful constants to add to **wp-config.php** to enable debugging.

 - [wp-content/uploads/.htaccess](https://github.com/DreadyCrig/Random-WP-Security/blob/master/wp-content/uploads/.htaccess)

Inside of the uploads folder, this **.htaccess* prevent PHP or any other bad code from being executted
in the uploads folder.  It's a common way for people to install WebShell Kits is to upload files as images.

 - [wp-content/themes/library/](https://github.com/DreadyCrig/Random-WP-Security/tree/master/wp-content/themes/library)

This directory should probably be moved to your active theme folder, and include the files you want
to use from it in your **functions.php** file.


#### Library Files

 - [/library/sc_functions.php](https://github.com/DreadyCrig/Random-WP-Security/tree/master/wp-content/themes/library/sc_functions.php)

This file includes a assortment of stuff that I personally use on every custom built WP site,  read thru the file and remove what you
do not require.  A list everything it does is


 - Creates a ALL Settings link under admin settings menu. Quite handy
 - Adds SEO Meta tags based on the post content and title, also adds robots tags.
 - Fix title, used to fix the home page title
 - Removes some menu items from the admin menu
 - Removes version number from styles/scripts to allow far future headers
 - Removes WP Version Number
 - Removes Windows Live Writer and Really Simple Discovery and a few other useless junk
 - Registers a post thumbnail type.
