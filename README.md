PHP Offline  Fonts 
==================

### **I corrected the original to make it work again**

Google changed their url, so instead of caching the fonts, it was pulling them from Google.

**Why couldn't I just download the fonts from Google myself?**

You can, but there's a ton of them. This way the script pulls the font and the css from Google, caches it, and serves it to the visitors to your site. It will only grab the fonts that are requested, not all eleventy gazillion fonts Google offers. The orignal saved the css using the user-agent, but I found that it created a ton of files, and I haven't yet seen it needed (maybe I will eventually) so I removed that part and the css is stored based on the query string alone.

**Why not just use Google fonts?**

Because some people don't like Big Brother Google spying on them. This creates a buffer between your site's visitors and Google so they can browse in privacy.

How it works...
------
NOTE: This only supports Google Fonts. 

This is set up how I am using it at https://fonts.amplighter.com. I created a "fonts" subdomain and housed the script and files there. The .htaccess allow any request to that domain to be funneled through the main script, duplicating the way Google fonts are accessed. 

So instead of:
```html
<link href='//fonts.googleapis.com/css?family=Lato:400,400italic,700' rel='stylesheet' type='text/css'>
```
You can use:
```html
<link href='//fonts.mydomain.com/css?family=Lato:400,400italic,700' rel='stylesheet' type='text/css'>
```
-----

**For more security...**

 Also, it may be wise to change the Access-Control-Allow-Origin in the .htaccess, to only allow your sites (unless you want everyone using it). You would change it to...
 
```
# ----------------------------------------------------------------------
# Allow loading of external fonts
# ----------------------------------------------------------------------
<FilesMatch "\.(ttf|otf|eot|woff|woff2)$">
    <IfModule mod_headers.c>
        SetEnvIf Origin "http(s)?://(www\.)?(mysite.com|mysite.org|myothersite.com|yetanothersite.com)$" AccessControlAllowOrigin=$0
        Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin
        Header merge Vary Origin
    </IfModule>
</FilesMatch>
```
 ...etc. Of course, changing the site names to the domain you want to allow access.
 
 -----




