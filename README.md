**Shadow Daemon** is a modular system that **detects and prevents known and unknown attacks against web applications**. It requires no source code changes, is very flexible and can be used for many different tasks, f.i. as *high-interaction honeypot* by security professionals to gather information about vulnerabilities, as *intrusion prevention system* by web administrators to protect internet sites or as *intrusion detection system* by network administrators to detect intruders.

# Documentation
This README is only a short guide to get you started quickly. For the complete user documentation please go to [https://shadowd.zecure.org/docs/current/](https://shadowd.zecure.org/docs/current/).

# Demo
A demonstration of the Shadow Daemon web interface can be found at [https://demo.shadowd.zecure.org/](https://demo.shadowd.zecure.org/).

# Installation
The first thing you have to do is download composer:
```
curl -s https://getcomposer.org/installer | php
```

Composer is an executable PHAR file which will download all dependencies and it also allows to configure the database settings. Just type:
```
php composer.phar install
```

Now you are able to add a new admin user:
```
php app/console swd:register --admin name email
```

Make sure that only *web* is accessible over the web server. You can test if everything worked out by visiting [http://localhost/config.php](http://localhost/config.php). Also make sure that *app/cache* and *app/logs* are writeable by the web server user.

# Usage
The first thing you should do is create a new profile for your connector(s). Activate the learning mode for the profile and gather learning data. If you have enough information open the rules generator and create rules. Activate the rules and disable the learning mode for the profile. Be prepared for false positives in the first days if the training data was not sufficient, but with some manual adjustments this can be fixed very fast and easy. Now your target is perfectly sealed.

# Acknowledgments
- Icons based on Iconic from useiconic.com
- Themes from bootswatch.com
