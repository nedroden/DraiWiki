<?php
/**
 * en_US
 * DraiWiki language pack
 *
 * DraiWiki:
 * @author		Robert Monden
 * @copyright	DraiWiki development team, 2017
 * @version		1.0 Alpha 1
 * @license		Apache 2.0
 *
 * Language pack:
 * @dialect		General American English
 * @country 	United States
 * @translator	Robert Monden
 * @license		Apache 2.0
 */

return [
    'management_panel' => 'Management panel',

    'back_to_wiki' => 'Back to wiki',

    'key' => 'Name',
    'value' => 'Value',

    'side_main' => 'Main',
    'side_config' => 'Configuration',
    'side_users' => 'User management',
    'side_security' => 'Security',
    'side_extend' => 'Extend',
    'side_help' => 'Help',

    'home' => 'Home',
    'logout' => 'Logout',

    'config_general' => 'General settings',

    'display_users' => 'Display users',
    'manage_groups' => 'Manage groups',
    'permissions' => 'Permissions',
    'bans' => 'Ban list',

    'registration' => 'Registration settings',

    'locale_management' => 'Locales',

    'locales_description' => 'Locales can be installed and managed here.',

    'dashboard_description' => 'Welcome to the management section. This is your dashboard, which is meant to provide an overview of things that are currently going on.',
    'dashboard_title' => 'Dashboard',

    'server_information' => 'Server information',
    'server_information_description' => 'Version numbers and other useful information can be viewed here.',

    'unknown' => 'unknown',
    'unknown_action' => 'We\'re not quite sure what you\'re trying to do here. Please try again.',

    'webserver' => 'Webserver',
    'wiki_information' => 'Wiki information',
    'server_software' => 'Server software:',
    'php_version' => 'PHP version:',
    'loaded_extensions' => 'Loaded extensions:',
    'db_version' => 'Database version:',
    'draiwiki_version' => 'DraiWiki version:',

    'days_js' => '"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"',

    'number_of_edits' => 'Number of edits',
    'number_of_edits_last_seven_days' => 'Number of edits in the last 7 days',

    'default_locale' => 'Default locale:',
    'default_templates' => 'Default template set:',
    'default_images' => 'Default images set:',
    'default_skins' => 'Default skin set:',

    'users_display' => 'Display users',
    'users_display_description' => 'View, modify and delete user accounts.',

    'username' => 'Username',
    'first_name' => 'First name',
    'last_name' => 'Last name',
    'email_address' => 'Email address',
    'sex' => 'Sex',
    'registration_date' => 'Registration date',
    'primary_group' => 'Primary group',
    'manage_buttons' => 'Manage',

    'detailed_system_information' => 'System information',

    'sysinfo_description' => 'Detailed system information can be viewed here. If you need any help with your DraiWiki installation, generate one of these and include them in your support request or bug report.',

    'server_operating_system' => 'Operating system:',
    'server_architecture' => 'Server architecture:',

    'text_format' => 'Text format',

    'settings_general' => 'General wiki settings',
    'settings_general_description' => 'On this page you are able to edit the most common wiki settings.',

    'settings_updated' => 'Settings updated',
    'above_int_limit' => 'Value is above int limit',

    'basic_settings' => 'Basic settings',
    'wiki_name' => 'Wiki name:',
    'wiki_name_desc' => 'The name of your wiki (e.g. My Wiki)',
    'wiki_slogan' => 'Slogan',
    'wiki_slogan_desc' => 'Your wiki\'s slogan',
    'wiki_email' => 'Website email',
    'wiki_email_desc' => 'This email address is used as sender when sending emails',

    'wiki_name_too_short' => 'The wiki name you entered was too short. Wiki names must be at least %u characters long',
    'wiki_name_too_long' => 'The wiki name you entered was too long. Wiki names can only be %u characters long',

    'wiki_email_too_short' => 'The email address you entered was too short. Email addresses must be at least %u characters long',
    'wiki_email_too_long' => 'The email address you entered was too long. Email addresses can only be %u characters long',
    'invalid_email' => 'Invalid email address',

    'features' => 'Features',
    'display_cookie_warning' => 'Display cookie warning',
    'display_cookie_warning_desc' => 'EU law requires all websites that use cookies to inform their users cookies are used. If you\'re on localhost, you can safely disable this option, but if you\'re not, you might want to leave it on.',
    'date_format' => 'Default date format',
    'date_format_desc' => 'The value of this setting is used as the default date format. Click <a href="http://php.net/manual/en/function.date.php" target="_blank">here</a> for more information about date formats.',
    'datetime_format' => 'Default datetime format',
    'datetime_format_desc' => 'The value of this setting is used as the default datetime format. Click <a href="http://php.net/manual/en/function.date.php" target="_blank">here</a> for more information about date formats.',

    'enable_this_feature' => 'Enable this feature',

    'settings_registration' => 'Registration settings',
    'settings_registration_description' => 'Here you can edit registration-related settings.',

    'general_registration_settings' => 'General',
    'enable_registration' => 'Enable registration',
    'enable_registration_desc' => 'When registration is disabled, the registration page can no longer be accessed. Existing users are still able to log in.',
    'enable_email_activation' => 'Enable email activation',
    'enable_email_activation_desc' => 'Should new user accounts be activated through an activation code sent by email?',

    'paths_and_urls' => 'Paths and urls',
    'base_path' => 'Base path',
    'base_path_desc' => 'This is the absolute path to your DraiWiki installation, without the trailing slash.',
    'base_url' => 'Base url',
    'base_url_desc' => 'This is the url to your DraiWiki installation, excluding the \'/index.php\' part (e.g. http://localhost/DraiWiki).',

    'cookies_and_sessions' => 'Cookies and sessions',
    'cookie_id' => 'Cookie name',
    'cookie_id_desc' => 'This name is used when creating cookies. You usually don\'t have to change this, but if you do, make sure to choose a combination of random letters, numbers and other characters. Also, be advised that if you change this, users who are currently logged in, will be logged out.',

    'session_name' => 'Session name',
    'session_name_desc' => 'This name is used as the session id. You usually don\'t need to change this.',

    'use_first_name_greeting' => 'Use user\'s first name for greeting',
    'use_first_name_greeting_desc' => 'If enabled, the software will greet users using their first name rather than username.',

    'site_and_file_maintenance' => 'Site maintenance',
    'maintenance_actions' => 'Maintenance actions',

    'general_maintenance' => 'Maintenance',
    'general_maintenance_description' => 'Here you can perform general maintenance tasks, such as removing obsolete data. Click on any of the action titles to perform the corresponding action.',

    'check_version' => 'Perform version check',
    'check_version_description' => 'This action sends you to the DraiWiki site and allows you to check whether or not you\'re using an up-to-date version of DraiWiki.',
    'remove_old_sessions' => 'Remove old sessions',
    'remove_old_sessions_description' => 'Remove all sessions older than 31 days.',

    'image_uploading' => 'Image uploading',
    'gd_image_upload' => 'Use GD module for image uploading',
    'gd_image_upload_desc' => 'An additional layer of security that should prevent XSS injection through images. Keep in mind that this requires the GD module for PHP. If that extension is not installed, this feature will not work.',

    'save' => 'Save',

    'recent_edits' => 'Recent edits',
    'recent_edits_description' => 'A list of the most recent edits (sorted by date) can be found here.',

    'article' => 'Article',
    'date' => 'Date',
    'updated_by' => 'Updated by',

    'manage_uploads' => 'Manage uploads',
    'upload_settings' => 'Configuration',
    'upload_settings_description' => 'Settings related to file uploading can be altered here',

    'upload_management' => 'Upload management',
    'upload_management_description' => 'This section allows you to manage the file uploading process.',

    'preview' => 'Preview',
    'filename' => 'Filename',
    'poster' => 'Uploaded by',
    'upload_date' => 'Upload date',
    'file_type' => 'Type of file',

    'file_avatar' => 'Avatar',
    'file_uploaded_image' => 'Image',

    'edit_user' => 'Edit',
    'remove_user' => 'Delete',

    'delete' => 'Delete',
    'setasdefault' => 'Make default',

    'cannot_delete_yourself' => 'You cannot delete yourself',
    'account_not_found' => 'User account not found',
    'cannot_delete_root' => 'You can disable the root account, but you cannot delete it',

    'statistics' => 'Statistics',
    'number_of_edits_stats' => 'Number of edits:',
    'number_of_articles' => 'Number of articles:',
    'number_of_users' => 'Number of activated users:',

    'installed_locales' => 'Installed locales',

    'id' => 'ID',
    'code' => 'Language code',
    'native' => 'Name',
    'dialect' => 'Dialect',
    'software_version' => 'Software version',
    'locale_version' => 'Locale version',

    'upload_before_install' => 'Before you can install a new locale, its files need to be in the /locales directory. The software will detect directories containing uninstalled locales and will offer to install them for you. If you\'re not sure how to do this, have a look at the other folders in that directory.',

    'no_locale_code' => 'No locale code set.',
    'no_id_specified' => 'No locale id specified.',
    'locale_exists' => 'Cannot install locale because it has already been installed.',
    'invalid_locale_code' => 'Invalid locale code.',
    'locale_does_not_exist' => 'The specified locale does not exist.',
    'no_locale_files_found' => 'No langinfo.xml file was found.',
    'missing_locale_files' => 'Missing locale files for one or more locales.',
    'cannot_delete_fallback_locale' => 'You cannot delete the fallback locale',

    'uninstalled_locale_detected' => 'DraiWiki has detected a directory for uninstalled locale <strong>%s</strong>. Click <a href="%s">here</a> to install this locale.',

    'actions' => 'Actions',

    'new_homepage_title' => 'Homepage',
    'new_homepage_body' => 'This is just a temporary homepage.',

    'this_will_delete_things' => 'Are you sure you want to delete this locale? Keep in mind that ALL ARTICLES that belong to this locale WILL BE PERMANENTLY DELETED.<br /><br />Please do not click the <em>okay</em> button unless you understand what the consequences are.',

    'execute_task' => 'Execute task',
    'unknown_maintenance_task' => 'The task you tried to execute was not recognized. It may have been deprecated or the URL may have been incorrect. Please try again.',
    'task_executed' => 'The task was successfully executed.',

    'empty_error_log' => 'Empty error log',
    'empty_error_log_description' => 'This task will clear the error log. This might be desirable if your error log is too large.'
];