# TSSSaver
An online interface for TSSChecker.
<br>
Pretty crappy code as of now<br><br>

Preview: https://tsssaver.1conan.com

#Features
- Automated
- Download to ZIP
- Save to Google Drive
- Save to Dropbox
- Instant blob saving (For new submissions)
- Google Recaptcha
- No frameworks for speedy loading
- Two themes (by <a href="https://www.reddit.com/user/MareddySaiKiran">/u/MareddySaiKiran</a>)

#Requirements
- MySQL/MariaDB
- PHP 5.5+
- PHP-MySQLi
- Cron (Optional)
- TSSChecker
- Linux

#Installation
1. Import the sql file<br>
2. Modify inc/config.php to your URL + database config<br>
3. Modify index/footer.html to your URL<br>
4. Download https://git.io/v1bM2 then place the extracted binary in your web root.

(optional)
Set cron to run cron.php on your preferred interval.<br>
For every 12am
```
0 0 * * * cd /path/to/your/web/root && php cron.php
```

(optional)
Change theme:<br>
1. Remove rename style.css to style-white.css<br>
2. Rename style-black.css to style.css<br>

#How to get the auto index stuff
I used nginx for that. Here is the config for it. 
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
