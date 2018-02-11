# TSSSaver
An online interface/frontend for TSSChecker.<br>

Preview: https://tsssaver.1conan.com

# Features
- Download to ZIP
- Save to Google Drive
- Save to Dropbox
- Instant blob saving
- Google Recaptcha

# Requirements
- MySQL/MariaDB
- PHP 5.5+ (7+ Recommended)
- PHP-pdo-mysql
- shell_exec()
- TSSChecker
- img4tool
- Cron (Optional)

# Installation
---
Coming soon.

# How to get the auto index stuff
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
# License
---
Licensed under MIT
Exceptions in the licensing:<br>
@jakeajames, @nstuj, or "Arshia Shojaian" is not allowed to use any of the code here.
