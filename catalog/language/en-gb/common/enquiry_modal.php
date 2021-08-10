<?php

// Entry
$_['entry_name']        = 'Name';
$_['entry_subject']     = 'Subject';
$_['entry_email']       = 'Email';
$_['entry_telephone']   = 'Contact No.';
$_['entry_enquiry']     = 'Message';

// Email
$_['email_subject']  	= 'Enquiry %s';

// Errors
$_['error_name']        = 'Name must be between 3 and 32 characters!';
$_['error_subject']     = 'Subject must be between 3 and 32 characters!';
$_['error_telephone']   = 'Contact No. must be all numbers';
$_['error_email']       = 'Email Address does not appear to be valid!';
$_['error_enquiry']     = 'Message must be between 10 and 300 characters!';
$_['error_featuredProduct'] = 'Enquiry must be between 10 and 300 characters!';

// AJ Apr 15: added Hints; it's copied from language file of home.php
// because validation is done in client's browser. NOT true for mobile phones. However, treat it as a known issue
// AJ Apr 21: remarked becausse we send back 
// $_['hint_name']        = 'Between 3 and 32 characters';
// $_['hint_subject']     = 'Between 3 and 32 characters';
// $_['hint_telephone']   = 'Numbers only';
// $_['hint_email']       = 'Valid Email address';
// $_['hint_enquiry']     = 'Message between 10 and 300 characters';