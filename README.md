SlimJim
=======

### WHY! 

SlimJim was born out of a need for a simple auto update script which would update multiple development/test environments every time someone commits to their respective Github repository.

I know there are many deployment/build scripts out there like whiskey_disk, Vlad and Capistrano which can do this if coupled with a CI server like cijoe, Jenkins, etc. 

But I found them to be more complicated to setup just for a basic need i.e to simply update a development/test environment using a post-receive hook without any manual user interaction on behalf of the committer besides ``git push...``

### INSTALLATION 

Now lets get to it. To configure SlimJim on your server follow these steps:

**Setup site and DB**

Basic LAMP setup should suffice. Everything you need is in this repo. I'm using a PHP micro-framework called Slim (thus the name!).

Run slimjim.sql on your MySql server

For all the projects that you want to auto-update, add the name of the github repo, branch and the physical path on your server to the Projects table

**Install & setup [incron](http://inotify.aiken.cz/?section=incron&page=about&lang=en)**

When the site receives a payload from the post-receive hook it will drop a request in the /requests folder. Incron is needed to listen for that event and trigger the deploy script.

``root@yourhost:/# apt-get install incron``

Add the root user to your allow list

``root@yourhost:/# vi /etc/incron.allow``

Add this, save and quit:

``root``

Now watch your requests directory for updates

``root@yourhost:/# incrontab -e``

Add this, save and quit:

``/srv/www/slimjim.yourcompany.com/public_html/requests/ IN_CREATE php /srv/www/slimjim.yourcompany.com/public_html/deploy.php $#``

**Permissions**

Give execute permissions to the deploy script

``root@yourhost:/# chmod +x deploy.php``

Give permissions to pull from github to the root user (make sure to leave the password empty) 

``root@yourhost:/# ssh-keygen -t rsa -C "root@yourhost"``

Copy and paste the contents from ~/.ssh/id_rsa.pub to Account Settings > SSH Keys > Add SSH key on Github

**Add Post-Receive URL**

Go to Admin -> Service Hooks and add this URL:

``http://slimjim.yourcompany.com/deploy``

That's it! Now sit back and watch Jim do the tedious work! :)