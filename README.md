SlimJim
=======

### WHY? 

SlimJim was born out of a need for a simple auto update script which would update multiple development/test environments every time someone commits to their respective GitHub or BitBucket repositories.

I know there are many deployment/build scripts out there like whiskey_disk, Vlad and Capistrano which can do this if coupled with a CI server like cijoe, Jenkins, etc. 

But I found them to be more complicated to setup just for a basic need i.e to simply update a development/test environment using a post-receive hook without any manual user interaction on behalf of the committer besides ``git push...``

### INSTALLATION 

To configure SlimJim on your server just follow these 4 steps:

1) **Setup site and DB**

Basic LAMP website setup should suffice. Everything you need is in this repo. I'm using a PHP micro-framework called Slim (thus the name!) which requires PHP 5.3.0 or newer.

Run `slimjim.sql` on your MySql server. 

Copy `config.sample.php` to `config.php` in the root folder and modify the following variables as needed:

    class CUSTOM_CONFIG {
        /* Paths */
       public static $ROOT_PATH = '/srv/www/slimjim.yourcompany.com/public_html/';
    
        /* MySQL */
        public static $DB_NAME	= 'slimjim';
        public static $DB_HOST  = 'localhost';
        public static $DB_USER	= 'root';
        public static $DB_PASS	= '';
    }

For all the projects that you want to auto-update, add the name of the github repo, branch and the physical path on your server to the projects table.

Alternatively, you can also manage projects and other settings by going to the administrative interface located at /admin

2) **Install & setup [incron](http://inotify.aiken.cz/?section=incron&page=about&lang=en)**

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

3) **Permissions**

Give execute permissions to the deploy script

``root@yourhost:/# chmod +x deploy.php``

Allow writing to the requests folder

``root@yourhost:/# chmod 777 /srv/www/slimjim.yourcompany.com/public_html/requests``

Give permissions to pull from github to the root user (make sure to leave the password empty) 

``root@yourhost:/# ssh-keygen -t rsa -C "root@yourhost"``

Copy the contents from ~/.ssh/id_rsa.pub and [add to GitHub][1] or [add to BitBucket][2]

4) **Add Post-Receive Webhook**

Finally, add the appropriate one of these URLs as a webhook into your repository settings:

For GitHub: ``http://slimjim.yourcompany.com/gh_hook``

For BitBucket: ``http://slimjim.yourcompany.com/bb_hook``

That's it! Now sit back and watch SlimJim do the tedious work! :)

### CONTRIBUTE!

Now if you like what this does, feel free to improve upon code. Just follow these steps to contribute:

1. Fork it
2. Create your feature branch (``git checkout -b my-new-feature``)
3. Commit your changes (``git commit -am 'Add some feature'``)
4. Push to the branch (``git push origin my-new-feature``)
5. Issue a [pull request](https://help.github.com/articles/using-pull-requests) to my **develop** branch
6. I will test out your cool feature on develop and do a release to master soon thereafter! :)


  [1]: https://help.github.com/articles/generating-ssh-keys
  [2]: https://confluence.atlassian.com/display/BITBUCKET/Add+an+SSH+key+to+an+account