# psybbs

A GPLv3/CC licensed Bulletin Board Software using PHP/MYSQL

###What is psyBBS? 
psyBBS is a platform for managing discussions, a de-facto web forum portal. It is being put together for learning and personal use at the moment. What does psyBBS stand for? No, it has nothing to do with the korean guy. Pronounced psybbs as in 'sye'bee'bee'ess. Like sci-fi, or psycho. It doesn't really mean anything.

##NOTE
This is and will be full of bugs right now, consider this software as pre-alpha quality. Some features are missing and/or need to be rewritten, so it's not very usable as of now. Most of the functions handling $_GET and $_POST input *should* be sanitised already. Be cautious however.

##Features
* Splash page
* Topic listing
* Thread listing (with page filter)
* Post listing (with page filter)
* BBCode
* Web based administration
* Login / Registration captcha
* Global user customization
* Local user customization
* Simple stats
* Gravatar support
* Search querys

##Project Goals
* W3C XHTML / W3C CSS3 valid
* SQLi and XSS resistant
* Fast
* Easy to administer
* Extendable with plugins (possibly)

##How to Install
* Clone the master branch into your web server root
* Add your MySQL server connectiont to include/config-example.php
* Rename it to include/config.php
* Navigate to index.php
* When processed, 'include/top.php' will check if a database exists, and automatically creates it
* Log in with your primary admin account and click the 'settings' tab in the userbar for further configuration

##Preview
![screenshot67](https://cloud.githubusercontent.com/assets/1535179/8247930/7423e92c-1650-11e5-9ad6-294054d4cdab.png)
![screenshot68](https://cloud.githubusercontent.com/assets/1535179/8247931/75527340-1650-11e5-91c6-e3f675156af0.png)
![screenshot69](https://cloud.githubusercontent.com/assets/1535179/8247933/7647f432-1650-11e5-9f85-920a4d04c18a.png)
![screenshot70](https://cloud.githubusercontent.com/assets/1535179/8247934/770ef244-1650-11e5-8b75-82d65bc7ed14.png)


##Licensing
The code of psyBBS is released under the General Public License version 3. 
Graphics/Artwork are released under various Creative Commons licenses (no stricter that cc-by-sa).
Themes/plugins are advised to be released as any "free" license of your choosing (MIT, GPL, BSD, etc)
