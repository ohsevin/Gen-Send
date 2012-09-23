Gen&Send
==============

Installation:

1. Rename config.php.default to config.php
2. Setup config.php with your details (paths, DB access etc - make sure you already have a DB with user setup)
3. Import the DB script in db/ into your Database
4. Make sure tmp/* is writable by the webserver owner (e.g. www-data, apache, whatever)
5. Setup a cron task to run: SITE_URL/securesend/cron_update/$hash where $hash is the hash you set in your config file - it only needs to run once per day preferably at midnight

curl -s -o /dev/null "SITE_URL/securesend/cron_update/$hash"   

I think that's just about it! After that, you can change the styles if you want.

This does use Apache specific re-writes in the .htaccess files as standard.

For Hiawatha do the following:

1. Set the /public/ dir as the website root, e.g. /path/to/mfyu/public
2. Make sure mfyu/tmp/* is writable by the webserver owner (e.g. www-data, apache, whatever -- this applies to any webserver, not just Hiawatha)
3. Use the following Toolkit:
UrlToolkit {
   ToolkitID = mfyu
   RequestURI exists Return
   Match (.)\?(.) Rewrite $1&$2 Continue
   Match ^/(.*) Rewrite /index.php?url=$1
}

The only DB driver I've tested this with is MySQL - others I'll look at adding eventually - it uses PDO for the database interaction.

/public/ holds the CSS, images, JS etc.
/application/views/ holds the view template files.