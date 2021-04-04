# Open Source Project Catalogue

## Overview
Building a vibrant community around open source development is an important part of our work. We've launched a new website which we hope will serve as a thriving hub for the open source community and drive power open innovation in Sri Lanka.

The site will feature open source best practices and resources, as well as the latest news, events and outreach programs. You can use the site to register your open source projects, search for opportunities to contribute and search for technical and functional experts. You can even register your technical or functional expertise and connect with folks who need expert guidance.

## Setting-up the server
To run the server, first a setup is needed:

1) Copy `.env.example` file and paste to the same root directory.

(2) Change the copied file to `.env`.

(3) Open `.env`.

### Configure the Database Connection
Enter the Database Connection details like the following.

```
DB_USERNAME=
DB_PASSWORD=
DB_HOST=
DB_PORT=
DB_DATABASE=
```

### Configure Google OAuth credentials
(1) To create credentials, go to the [Google Credentials page](https://console.developers.google.com/apis/credentials) and click on the **Create credentials > OAuth client ID**.

(2) Copy **Client ID** and **Client secret** in `.env` like the following.

```
OAUTH_GOOGLE_ID=
OAUTH_GOOGLE_SECRET=
```

### Configure Facebook OAuth credentials
(1) Register the app on [developers.facebook.com](https://developers.facebook.com/).

(2) Once logged, click on the **Create a new app ID** and fill in the form.

(3) In the menu, click on **Settings > General**.

(4) Copy **App ID** and **Secret key** in `.env` like the following.

```
OAUTH_FACEBOOK_ID=
OAUTH_FACEBOOK_SECRET=
```

### Configure Mailer Connection
(1) [Create a new email account](https://accounts.google.com/signup/v2/webcreateaccount?continue=https%3A%2F%2Faccounts.google.com%2FManageAccount%3Fnc%3D1&dsh=S-1257577869%3A1617510605810493&gmb=exp&biz=false&flowName=GlifWebSignIn&flowEntry=SignUp) in **GMail**.

(2) Configure the Mailer Connection like the following.

```
MAILER_USERNAME=
MAILER_DOMAIN=
MAILER_PASSWORD=
```

### Install dependencies

```
composer install
```

### Create database

```
php bin/console doctrine:database:create
```

### Run migrations

```
php bin/console doctrine:migrations:migrate
```

### Running the server

```
symfony server:start
```

## Help

* [Symfony Documentation](https://symfony.com/doc/current/index.html)

* [Easily implement Google login with Symfony 4](https://hugo-soltys.com/blog/easily-implement-google-login-with-symfony-4)

* [Easily implement Facebook login with Symfony 4](https://hugo-soltys.com/blog/easily-implement-facebook-login-with-symfony-4)