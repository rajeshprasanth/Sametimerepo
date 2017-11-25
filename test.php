#!/usr/bin/php -q
<?php
#
# Configuration: Enter the url and key. That is it.
#  url => URL to api/task/cron e.g #  http://yourdomain.com/support/api/tickets.json
#  key => API's Key (see admin panel on how to generate a key)
#  $data add custom required fields to the array.
#
#  Originally authored by jared@osTicket.com
#  Modified by ntozier@osTicket / tmib.net

// If 1, display things to debug.
$debug="0";

// You must configure the url and key in the array below.

$config = array(
        'url'=>'http://192.163.218.190/api/tickets.json',  // URL to site.tld/api/tickets.json
		'key'=>'5E104F38C476E06EB8EC1980AC7410FC'  // API Key goes here
);
# NOTE: some people have reported having to use "http://your.domain.tld/api/http.php/tickets.json" instead.
		
# Fill in the data for the new ticket, this will likely come from $_POST.
# NOTE: your variable names in osT are case sensiTive. 
# So when adding custom lists or fields make sure you use the same case
# For examples on how to do that see Agency and Site below.
$data = array(
    'name'      =>      'John Doe',  // from name aka User/Client Name
    'email'     =>      'john@gmail.com',  // from email aka User/Client Email
	'phone' 	=>		'1234567890',  // phone number aka User/Client Phone Number
    'subject'   =>      'Test API message',  // test subject, aka Issue Summary
    'message'   =>      'This is a test of the osTicket API',  // test ticket body, aka Issue Details.
    'ip'        =>      '192.163.218.190', // Should be IP address of the machine thats trying to open the ticket.
	//'topicId'   =>      '1'; // the help Topic that you want to use for the ticket 
	//'Agency'  =>		'58', //this is an example of a custom list entry. This should be the number of the entry.
	//'Site'	=>		'Bermuda'; // this is an example of a custom text field.  You can push anything into here you want.	
    
);

# more fields are available and are documented at:
# https://github.com/osTicket/osTicket-1.8/blob/develop/setup/doc/api/tickets.md

if($debug=='1') {
  print_r($data);
  die();
}

# Add in attachments here if necessary
# Note: there is something with this wrong with the file attachment here it does not work.


#pre-checks
function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#set timeout
set_time_limit(30);

#curl post
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $config['url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result=curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code != 201)
    die('Unable to create ticket: '.$result);

$ticket_id = (int) $result;

# Continue onward here if necessary. $ticket_id has the ID number of the
# newly-created ticket

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}
?>