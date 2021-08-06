<?php
/* AJ Apr 12: This language file is added by AJ. It is directly copied from the counter part of category.
   It is for the use of "Enquiry Now" Modal Window on the home page. The products are inside the featured products slick slider */
$_['text_success']	    = '<p>Your enquiry has been successfully sent to the store owner!</p>';

// Entry
$_['entry_name']        = 'Name';
$_['entry_subject']     = 'Subject';
$_['entry_email']       = 'Email';
$_['entry_telephone']   = 'Contact No.';
$_['entry_enquiry']     = 'Message';
$_['entry_featuredProduct'] = 'Relevant Product'; // AJ Apr 14: added for email body (Enquire Now)

// Email
$_['email_subject']  	= 'Enquiry %s';

// AJ Apr 14: added Hints. 
// because validation is done in client's browser
// AJ Ap4 20: remarked, because we Captcha requires validation at server side. 
// $_['hint_name']        = 'Between 3 and 32 characters';
// $_['hint_subject']     = 'Between 3 and 32 characters';
// $_['hint_telephone']   = 'Numbers only';
// $_['hint_email']       = 'Valid Email address';
// $_['hint_enquiry']     = 'Message between 10 and 300 characters';

// AJ Apr 20, copied from category.php language file. modified accordingly.
// Errors
$_['error_name']        = 'Name must be between 3 and 32 characters!';
$_['error_subject']     = 'Subject must be between 3 and 32 characters!';
$_['error_telephone']   = 'Contact No. must be all numbers';
$_['error_email']       = 'Email Address does not appear to be valid!';
$_['error_enquiry']     = 'Message must be between 10 and 300 characters!';
$_['error_featuredProduct'] = 'Enquiry must be between 10 and 300 characters!';