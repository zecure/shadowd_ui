![Logo](http://shadowd.zecure.org/img/logo_small.png)

**Shadow Daemon** is a collection of tools to **detect**, **record** and **prevent** **attacks** on *web applications*. Technically speaking, Shadow Daemon is a **web application firewall** that intercepts requests and filters out malicious parameters. It is a modular system that separates web application, analysis and interface to increase security, flexibility and expandability.

This component can be used to manage profiles, rules and recorded attacks.

# Documentation
For the full documentation please refer to [shadowd.zecure.org](https://shadowd.zecure.org/).

# Demo
A demonstration of this web interface can be found at [demo.shadowd.zecure.org](https://demo.shadowd.zecure.org/).

# Installation
The first thing you have to do is to download composer:

    curl -s https://getcomposer.org/installer | php

Composer is an executable PHP archive which will download all dependencies and it also allows to configure the database settings. To start the installation just run:

    php composer.phar install

If the installation is successful you are able to add a new admin user:

    php app/console swd:register --env=prod --admin --name=arg (--email=arg)

Make sure that *app/cache* and *app/logs* are writeable by the web server user.
