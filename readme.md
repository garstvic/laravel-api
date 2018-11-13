Laravel 5 Blog Application

// Install framework

$ rm README.md php.ini hello-world.php

$ sudo composer self-update

$ composer create-project laravel/laravel ./laravel --prefer-dist

$ shopt -s dotglob

$ mv laravel/* ./

$ rm -rf laravel

// Apache2

$ sudo vim /etc/apache2/sites-enabled/001-cloud9.conf

    // Change this line
    DocumentRoot /home/ubuntu/workspace

    // To following
    DocumentRoot /home/ubuntu/workspace/public

$ sudo composer update

// DB

$ mysql-ctl install

$ c9 .env

    // Change this lines
    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret

    // To following
    DB_DATABASE=c9
    DB_USERNAME={{ Root User Name from output of command 'mysql-ctl isntall'}}
    DB_PASSWORD=

$ phpmyadmin-ctl install

$ mysql-ctl start

Open url from output of command 'phpmyadmin-ctl install' : type {{ Root User Name from output of command 'mysql-ctl install'}} and blink password

Run Project

// Clone Application from GitHub

$ git clone https://github.com/garstvic/laravel-blog-app.git

$ rsync -avh ./laravel-blog-app/ ./

$ rm -rf ./laravel-blog-app

$ composer dump-autoload

$ php artisan migrate

$ php artisan db:seed