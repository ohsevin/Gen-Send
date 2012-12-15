Gen&Send
==============

Installation:

1. Rename config.default.php to config.php
2. Setup config.php with your details (paths, DB access etc - make sure you already have a DB with user setup)
3. Import the DB script in db/ into your Database
4. Make sure tmp/* is writable by the webserver owner (e.g. www-data, apache, whatever)
5. Setup a cron task to run: SITE_URL/securesend/cron_update/$hash where $hash is the hash you set in your config file - it only needs to run once per day preferably at midnight

curl -s -o /dev/null "SITE_URL/securesend/cron_update/$hash"   

I think that's just about it! After that, you can change the styles if you want.

This does use Apache specific re-writes in the .htaccess files as standard.

For Hiawatha do the following:

1. Set the /public/ dir as the website root, e.g. /path/to/gensend/public
2. Make sure gensend/tmp/* is writable by the webserver owner (e.g. www-data, apache, whatever -- this applies to any webserver, not just Hiawatha)
3. Use the following Toolkit:

UrlToolkit {  
   ToolkitID = gensend  
   RequestURI exists Return  
   Match (.)\?(.) Rewrite $1&$2 Continue  
   Match ^/(.*) Rewrite /index.php?url=$1  
}

The only DB driver I've tested this with is MySQL - others I'll look at adding eventually - it uses PDO for the database interaction.

Requires mcrypt

/public/ holds the CSS, images, JS etc.
/application/views/ holds the view template files.

The system was built off the back of a framework I tweaked and started reworking & redeveloping. Now, I figured to have it as it's own stand alone application. I'm gradually removing framework elements that aren't needed anymore.