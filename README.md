# TSSSaver
An online interface/frontend for TSSChecker.<br>
Pretty crappy code as of now<br><br>
Licensed under MIT License. Yes, yes, it is licensed under MIT License.

Preview: https://tsssaver.1conan.com

#Features
- Automated
- Download to ZIP
- Save to Google Drive
- Save to Dropbox
- Instant blob saving (For new submissions)
- Google Recaptcha
- No web frameworks for speedy loading
- Two themes (by <a href="https://www.reddit.com/user/MareddySaiKiran">/u/MareddySaiKiran</a>)

#Requirements
- MySQL/MariaDB
- PHP 5.5+ (7+ Recommended)
- PHP-MySQLi
- shell_exec() and exec()
- TSSChecker
- Linux (Debian or debian derivatives)
- Cron (Optional)

#Installation
1. Download all the files in your web root.<br>
2. Import devices.sql to MySQL/MariaDB. (phpMyAdmin or CLI)
3. Modify inc/config.sample.php to your preferences<br>
4. Rename inc/config.sample.php to inc/config.php<br>
5. Modify index/footer.html according to your preferences.<br>
6. Make bin/ directory.<br>
7. Download https://git.io/v1bM2 and uncompress to bin/ directory.<br>
8. Download img4tool and uncompress. (http://api.tihmstar.net/builds/img4tool/img4tool-latest.zip)<br>
9. Rename img4tool_linux to img4tool then move to bin/<br>
10. Done :) <br>
<br>
<br>
(optional)<br>
Set cron to run cron.php on your preferred interval.<br>
For every 12am
```
0 0 * * * cd /path/to/your/web/root && php cron.php
```
<br>
(optional)<br>
Change theme:<br>
1. Remove rename style.css to style-white.css<br>
2. Rename style-black.css to style.css<br>
<br>
(optional)<br>
Save to dropbox<br>
1. Get an APP Key from dropbox.<br>
2. Replace "YOUR_APP_KEY" in /index/footer.html with your key

#How to get the auto index stuff
I used nginx with fancyindex module for that. Here is the config for it. 
```
location /shsh {
	index index.php;
	fancyindex on;
	fancyindex_exact_size off;
	fancyindex_header "/index/header.html";
	fancyindex_footer "/index/footer.html";
	fancyindex_localtime on;

}
```

#License

MIT License

Copyright (c) Andre Bongon 2016 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
