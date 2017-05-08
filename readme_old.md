# DraiWiki
## 1. Introduction to DraiWiki
### 1.1. What is DraiWiki?
DraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.

### 1.2. Why use DraiWiki?
There are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.

First of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you'll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it'll only take a few seconds to set up.

It also has built-in multi-language support, meaning you won't need an extension.

The admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you'll be able to fix it from within the admin panel. That's not all, however, because the admin panel also allows you to make changes without much effort.

## 2. Installation
### 2.1. Server requirements
#### 2.1.1. Minimum
* PHP 5.6+
* MariaDB / MySQL
* PDO extension
* Composer
* NPM

#### 2.1.2. Recommended
* PHP 7.0+
* MariaDB
* PDO extension
* Composer
* NPM

### 2.2. How to install
1. Install Composer and NPM. If you're on a shared hosting and can't use the terminal, look at section 2.3
2. cd to your http directory
3. Run the following command in your command prompt or terminal: git clone http://github.com/Chistaen/DraiWiki.git
4. Use Composer to install the required packages (composer install)
5. Use NPM to install the required JS libraries (npm install)
6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting
7. Import the database tables (install.sql)
8. Enjoy!

### 2.3. Troubleshooting
#### 2.3.1. Help! I don't have access to a terminal!
If you're on a shared hosting that doesn't allow you to install Composer/NPM, don't worry. There's another solution. Just download the files to your computer and install the Composer and NPM packages from your computer's terminal. Then re-upload the files to your hosting. Happy writing!

#### 2.3.2. Help! My hosting doesn't support the minimum required PHP version!
At the time of writing, the minimum required PHP version is 5.6. If your host is running PHP 5.5, you should consider asking them to upgrade their PHP version. If your host is using an even older version, you should _insist_ that they upgrade their PHP as soon as possible.
