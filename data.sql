-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2017 at 05:33 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `{db_prefix}agreement` (`id`, `body`, `locale_id`) VALUES
  (1, 'This is the user agreement', 1);

INSERT INTO `{db_prefix}article` (`id`, `title`, `locale_id`, `status`) VALUES
  (1, 'Homepage', 1, 1),
  (2, 'About us', 1, 2);

INSERT INTO `{db_prefix}article_history` (`id`, `article_id`, `user_id`, `body`, `updated`) VALUES
  (1, 1, NULL, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 7.1+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone https://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Run the DDL (table creation) and DML (data insertion) .sql files: ddl.sql and dml.sql.\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n## 3. Open positions\r\nWe\'re always looking to expand our team. Currently, the following positions are open:\r\n* Development\r\n* Quality Assurance\r\nGo to our forum to apply:\r\nhttps://draiwiki.robertmonden.com/forum', '2017-06-16 15:20:06'),
  (2, 2, NULL, 'This is the about page', '2017-06-16 18:47:49');

INSERT INTO `{db_prefix}group` (`id`, `title`, `color`, `permission_group_id`) VALUES
  (1, 'Root', '000000', NULL),
  (2, 'Administrator', 'db794e', 1),
  (3, 'Banned', '000000', 2),
  (4, 'Regular user', '000000', 4),
  (5, 'Guest', '000000', 5);

INSERT INTO `{db_prefix}homepage` (`article_id`, `locale_id`) VALUES
  (1, 1);

INSERT INTO `{db_prefix}locale` (`id`, `code`) VALUES
  (1, 'en_US');

INSERT INTO `{db_prefix}permission_group` (`id`, `title`, `permissions`) VALUES
  (1, 'Admin', 'edit_articles:a;soft_delete_articles:a;manage_site:a;print_articles:a;view_article_history:a;upload_images:a;print_articles:a;find_article:a;assign_translations:a;remove_from_translation_group:a'),
  (2, 'Banned', 'edit_articles:d;soft_delete_articles:d;print_articles:d;view_article_history:d;upload_images:d;print_articles:d;find_article:d;assign_translations:d;remove_from_translation_group:d'),
  (3, 'Moderator', 'edit_articles:a;soft_delete_articles:a,print_articles:a;view_article_history:a;upload_images:a;print_articles:a;find_article:a;assign_translations:a'),
  (4, 'Regular user', 'edit_articles:a;print_articles:a;view_article_history:a;upload_images:a;print_articles:a;find_article:a;assign_translations:a'),
  (5, 'Guest', 'print_articles:a;view_article_history:a;print_articles:a;find_article:a');

INSERT INTO `{db_prefix}setting` (`key`, `value`) VALUES
  ('min_title_length', '1'),
  ('max_title_length', '60'),
  ('min_body_length', '5'),
  ('max_body_length', '0'),
  ('max_email_length', '40'),
  ('max_password_length', '30'),
  ('max_first_name_length', '15'),
  ('max_last_name_length', '40'),
  ('max_username_length', '20'),
  ('min_username_length', '3'),
  ('min_password_length', '8'),
  ('min_first_name_length', '2'),
  ('min_last_name_length', '2'),
  ('password_salt', 'aLJ#D_d32?o87DS=-DSAdk./:'),
  ('min_email_length', '5'),
  ('templates', '{data:default_templates}'),
  ('images', '{data:default_image_set}'),
  ('skins', '{data:default_skin_set}'),
  ('enable_registration', '1'),
  ('enable_email_activation', '0'),
  ('wiki_email', 'draiwiki@localhost'),
  ('activation_code_length', '24'),
  ('display_cookie_warning', '1'),
  ('max_results_per_page', '20'),
  ('date_format', 'F j, Y, g:i a'),
  ('slogan', '{data:slogan}'),
  ('path', '{data:path}'),
  ('url', '{data:url}'),
  ('session_name', 'dw_session_Kalkhjasld'),
  ('cookie_id', 'dw_cookie_328970asdf__4jdam'),
  ('wiki_name', '{data:wiki_name}'),
  ('max_image_width', '300'),
  ('max_image_height', '300'),
  ('max_image_size_kb', '1024'),
  ('allowed_image_extensions', 'png;jpg;jpeg;gif'),
  ('gd_image_upload', '1'),
  ('min_image_width', '20'),
  ('min_image_height', '20'),
  ('max_image_description_length', '500'),
  ('datetime_format', 'F j, Y, g:i:a'),
  ('locale', '1'),
  ('max_search_term_length', '60'),
  ('max_finder_body_length', '750'),
  ('min_search_term_length', '3'),
  ('use_first_name_greeting', '0');

INSERT INTO `{db_prefix}user` (`id`, `username`, `password`, `email_address`, `sex`, `birthdate`, `first_name`, `last_name`, `ip_address`, `registration_date`, `group_id`, `secondary_groups`, `activated`) VALUES
  (1, 'root', '$2y$10$YUxKI0RfZDMyP284N0RTPOUE4ko1mljdwHNh.joGhu3HZYnxcyBvO', 'nobody@example.com', 0, '0000-00-00', 'Admin', 'Istrator', '127.0.0.1', '2017-07-30 18:41:48', 1, '', 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
