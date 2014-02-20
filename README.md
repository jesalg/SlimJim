SlimJim
=======

### WHY? 

SlimJim was born out of a need for a simple auto update script which would update multiple development/test environments every time someone commits to their respective Github repository.

I know there are many deployment/build scripts out there like whiskey_disk, Vlad and Capistrano which can do this if coupled with a CI server like cijoe, Jenkins, etc. 

But I found them to be more complicated to setup just for a basic need i.e to simply update a development/test environment using a post-receive hook without any manual user interaction on behalf of the committer besides ``git push...``

### INSTALLATION 

Now lets get to it. To configure SlimJim on your server follow these steps:

**Setup site and DB**

Basic LAMP setup should suffice. Everything you need is in this repo. I'm using a PHP micro-framework called Slim (thus the name!) which requires PHP 5.3.0 or newer.

Update the first line in deploy.php to point to the path of your SlimJim directory.

Run slimjim.sql on your MySql server. Update /index.php & /admin/index.php with appropriate host, username, and password for the database.

For all the projects that you want to auto-update, add the name of the github repo, branch and the physical path on your server to the projects table.

Alternatively, you can also manage projects and other settings by going to the administrative interface located at /admin

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

``/srv/www/slimjim.yourcompany.com/public_html/requests/ IN_CLOSE_WRITE php /srv/www/slimjim.yourcompany.com/public_html/deploy.php $#``

**Permissions**

Give execute permissions to the deploy script

``root@yourhost:/# chmod +x deploy.php``

Allow writing to the requests folder

``root@yourhost:/# chmod 777 /srv/www/slimjim.yourcompany.com/public_html/requests``

Give permissions to pull from github to the root user (make sure to leave the password empty) 

``root@yourhost:/# ssh-keygen -t rsa -C "root@yourhost"``

Copy and paste the contents from ~/.ssh/id_rsa.pub to Account Settings > SSH Keys > Add SSH key on Github

**Add Post-Receive URL**

Add the appropriate one of these URLs as a webhook in your repository settings:

For GitHub: ``http://slimjim.yourcompany.com/gh_hook``

For BitBucket: ``http://slimjim.yourcompany.com/bb_hook``

That's it! Now sit back and watch SlimJim do the tedious work! :)

### CONTRIBUTE!

Now if you like what this does, feel free to improve upon code. Just follow these steps to contribute:

* Fork SlimJim on GitHub & Clone your fork onto your machine

 ``git clone git@github.com:[YOUR_USERNAME]/SlimJim.git``

* Pull the *develop* branch from the upstream repository

 ``cd SlimJim``

 ``git remote add upstream git@github.com:jesalg/SlimJim.git``

 ``git fetch upstream``

 ``git checkout -b develop origin/develop``

* Keep develop up-to-date
  
 ``git fetch upstream``

 ``git rebase upstream/develop develop``

* Create a feature branch

 ``git flow feature start my_cool_feature``

* Hack Hack Hack
 
 Make sure to commit your work in bite-size chunks, so the commit log remains clear.

* When ready, push the feature branch to your origin
 
  ``git push origin my_cool_feature``

* Issue a [pull request](https://help.github.com/articles/using-pull-requests) to my *develop* branch

* I will test out your cool feature on develop and do a release to master soon thereafter! :)
