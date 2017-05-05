# Postrr

Postrr is a demo of a link sharing site created using PHP, MySql, Bootstrap, jQuery, and the Laravel Framework.  It allows multiple users to share links to other websites, as well as upload pictures and videos to be shared.

![Home Page](/screenshots/home.png)

## Requirements for installation.

These instructions cover installing Postrr to a local machine.  The process for installing in a production environment is similar, but will vary a bit based on the hosting provider and the web server used.

This installation needs the following:
* Greater than PHP 5.3 installed. http://php.net/downloads.php
* Composer dependency manager. https://getcomposer.org/download/
* MySql database. https://dev.mysql.com/downloads/

## Installation

First we'll navigate to the directory of your choice and clone the repository.
```
git clone https://github.com/TwoRoundWheels/Postrr.git
```

Then we'll cd into the Postrr directory.
```
cd Postrr
```

Next we'll use Composer to install Laravel and its dependencies.
```
composer install
```

We'll need to create a MySql database for Postrr to use.  Log in to MySql with your username and password.
```
mysql -u yourusername -pyourpassword
```

From the MySql command prompt create a database named postrr.  Once created, we can exit MySql.
```
CREATE DATABASE postrr;
\q
```

Then let's create an environment file to store our environment variables.  Copy the example file to a new file with the name '.env.local.php'.  Please note the "." at the beginning of both files.
```
cp .env.example .env.local.php
```

Then edit `.env.local.php` and add appropriate credentials for your database server. Fill in the values for `DATABASE_USER` and  `DATABASE_PASSWORD` as a string (don't delete the single quotes!) so Laravel is able to access the postrr database we created.  The code example uses nano, but any text editor will work for this step.   
```
nano .env.local.php
```

With the database now setup, we can use Artisan to migrate the table structure to our database.  This will build the tables and columns Postrr needs to use in our database.
```
php artisan migrate
```

Finally we'll generate application key which will be used for session and cookie encryption and password hashing.
```
php artisan key:generate
```

Postrr should be successfully installed now.

## Run server

We can use Artisan to act as a server on the local machine using this command.
```
php artisan serve
```

Now you will be able to navigate to `http://localhost:8000` from your browser and see the app.

## Registering and logging in.

In your browser navigate to `http://localhost:8000/users/register`.  This will pull up a form where you can register new users to have access to the app.  Fill in the 3 required fields and you will be redirected to the login page, where you can then enter the email address and password you selected.

### Disabling new users.

Open `.env.local.php` in a text editor and set the `NEW_USERS` value to `false`.  This prevents new users from signing up and keeps your app private.

## Setting up mail

Open `.env.local.php` in a text editor and fill in the value at the bottom with information from your mail server.  Setting the `PRETEND` value to `false` will allow emails actually be sent.  If the `PRETEND` value is `true`, emails are logged in `app/storage/logs/laravel.log`.

## Troubleshooting  


If you are receiving errors, try changing the permission of `app/storage` using this command from the root of the repository.
```
sudo chmod 777 -R app/storage
```

If you are receiving errors when uploading a file, you may need to change permissions on the `public/uploads` folder using this command from the root of the repository.

```
sudo chmod 775 -R public/uploads
```
## Built with:

*[Laravel 4.2](https://laravel.com/)
*[MySql](https://www.mysql.com/)
*[Bootstrap](getbootstrap.com/)
*[jQuery](https://jquery.com/)

## Screenshots

### Home Page
![Home Page](/screenshots/home.png)

### Create New Post
![Create New Post](/screenshots/post.png)

### Settings Page
![Settings](/screenshots/settings.png)

### Sign In Page
![Sign In Page](/screenshots/login.png)

### Example of Comments Page
![Comments](/screenshots/comments.png)

### User Profile Page
![User Profile](/screenshots/profile.png)
