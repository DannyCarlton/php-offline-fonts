PHP Offline  Fonts 
==================
[![Release](https://img.shields.io/github/release/DannyCarlton/php-offline-fonts.svg?style=flat)](https://github.com/DannyCarlton/php-offline-fonts/releases)
[![Apache 2.0 License](https://img.shields.io/badge/license-Apache%202.0-red.svg?style=flat)](https://github.com/DannyCarlton/php-offline-fonts/blob/master/LICENSE)
[![Gitter chat](http://img.shields.io/badge/gitter-open-1DCE73.svg?style=flat)](https://gitter.im/DannyCarlton/php-offline-fonts)

### **I corrected the original to make it work again**

Google changed their url, so instead of caching the fonts, it was pulling them from Google.

**Why couldn't I just download the fonts from Google myself?**
You can, but there's a ton of them. This way the script pulls the font and the css from Google, caches it, and serves it to the visitors to your site. It will only grab the fonts that are requested, not all eleventy gazillion fonts Google offers.

**Why not just use Google fonts?**
Because some people don't like Big Brother Google spying on them. This creates a buffer between your site's visitors and Google so they can browse in privacy.

How To
------
This only supports Google Fonts. 

 This is set up how I am using it at https://fonts.amplighter.com. I created a "fonts" subdomian and housed the script and files there. The .htaccess allow any request to that domain to be funneled through the main script, duplicating the way Google fonts are accessed. 

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
 
~
    Header set Access-Control-Allow-Origin "https://myfirstsite.com"
    Header set Access-Control-Allow-Origin "http://myfirstsite.com"
    Header set Access-Control-Allow-Origin "https://mysecond.com"
    Header set Access-Control-Allow-Origin "http://mysecond.com"
    Header set Access-Control-Allow-Origin "https://mythirdsite.com"
    Header set Access-Control-Allow-Origin "http://mythirdsite.com"
~
 ...etc.
 