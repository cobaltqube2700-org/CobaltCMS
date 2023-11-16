## tweaking php.ini

login to your box using your admin credentials (telnet, or preferably SSH if you can)

    su (enter password)

    vim /etc/httpd/php.ini

ensure the following line is uncommented (no semicolon at the start)

    extension=mysql.so

then restart apache

    /etc/rc.d/init.d/httpd restart 
