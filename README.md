Hallo Good Game Studio !

#Guidline:

---

1.	Go to /var/www/ folder OR set your virtual Host, in this task Default is /var/www
2.	run __git clone git@github.com:arbi/ggstudio.git ggs__ 
  * It's assumed that your folder name is ggs so set ggs folder for cloning.
3. 	Set your Database config in ggs/api/Config/database.config.php
4. 	I just set login process not Signup so execute this sql to add a default user.
  * execute sql: __mysql -u USERNAME -p < /var/www/ggs/sql/ggstudio.sql__
  *	username = tatjana
  *	password = halloGGS

5.	set url __http://localhost/ggs/api/View/index.phtml__ in your browser.
6.	Done.


