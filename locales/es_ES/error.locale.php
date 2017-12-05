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
    'something_went_wrong' => 'Something went wrong',
    'generic_error_message' => 'An error occurred. It is possible the error will go away after refreshing the page. If refreshing the page doesn\'t help, please try again later or contact the administrator',
    'fatal_core_exception' => 'Fatal core exception',
    'what_is_a_fatal_core_exception' => 'A fatal core exception has just occurred. A core exception is an error in one of the core classes of DraiWiki. These exceptions usually indicate missing files, but they can also be caused by other problems. Try refreshing the page to see if that helps or contact an administrator if you keep seeing this page.<br /><br />We sincerely apologize for any inconvenience this has caused you.',
    'yes_you_can' => 'Since you are the administrator, we\'re allowed to tell you stuff. This error message might help you out:<br />',
    'database_exception' => 'A database exception occurred',
    'what_is_a_database_exception' => 'Unfortunately the server has been unable to successfully process your database request. Please try again in a few minutes.',

    'query' => 'The exception was caused by the following query: ',
    'no_homepage_found' => 'No homepage found. Did you run the installer?',
    'homepage_id_not_a_number' => 'Homepage ID not a number. Not sure what happened here...',

    'access_denied' => 'Access denied',
    'access_denied_why' => 'You do not have access to this section.',

    'cant_proceed_exception' => 'Unable to proceed',
    'cannot_delete_homepage' => 'Homepages cannot be deleted.',
    'cannot_delete_article' => 'Cannot delete article. Are you sure it exists?',

    'could_not_send_mail' => 'Unable to send activation mail',
    'no_activation_code_given' => 'No activation code was given.',

    'registration_disabled' => 'At the moment it is not possible to register a new user account, as the wiki admin has disabled registration.',

    'could_not_upload_image' => 'Could not upload image',
    'could_not_assign_name' => 'Unable to rename the original file, because the integer limit was reached. We suppose congratulations are in order.',

    'image_not_found' => 'Could not load the requested image. Are you sure it exists?',
    'user_not_found' => 'User not found',

    'section_not_found' => 'Section not found',
    'invalid_article' => 'Article not found or no article specified',
    'group_invalid_locale' => 'Invalid locale group. Keep in mind that you cannot add two articles written in the same language to the same translation group.'
];