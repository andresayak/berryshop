Berryshop
=======================

Introduction
------------



Installation
------------

Using Composer (recommended)
----------------------------
Clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/andresayak/berryshop.git
    cd berryshop
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)


Using Git submodules
--------------------
Alternatively, you can install using native git submodules:

    git clone git://github.com/andresayak/berryshop.git --recursive

Virtual Host
------------
Afterwards, set up a virtual host to point to the htdocs/ directory of the
project and you should be ready to go!
