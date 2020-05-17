[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=zecure_shadowd_ui&metric=alert_status)](https://sonarcloud.io/dashboard?id=zecure_shadowd_ui)
![Logo](http://shadowd.zecure.org/img/logo_small.png)

**Shadow Daemon** is a collection of tools to **detect**, **record** and **prevent** **attacks** on *web applications*.
Technically speaking, Shadow Daemon is a **web application firewall** that intercepts requests and filters out malicious parameters.
It is a modular system that separates web application, analysis and interface to increase security, flexibility and expandability.

This component can be used to manage profiles, rules and recorded attacks.

# Documentation
For the full documentation please refer to [shadowd.zecure.org](https://shadowd.zecure.org/).

# Demo
A demonstration of this web interface can be found at [demo.shadowd.zecure.org](https://demo.shadowd.zecure.org/).

# Installation
Use [Composer](https://getcomposer.org/) to install the user interface.

    composer install

If the installation is successful you are able to add a new admin user via the terminal:

    php app/console swd:register --env=prod --admin --name=arg (--email=arg)

Make sure that *app/cache* and *app/logs* are writeable by the web server user.
