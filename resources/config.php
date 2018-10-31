<?php
$config = array (
	"db" => array (
		"dbname" => "guardian",
		"username" => "guardiandb",
		"password" => "guardiandbpw2018!",
		"host" => "localhost"
	),
	"mail" => array (
		"host" => "host33.checkdomain.de",
		"username" => "guardian@thral.de",
		"password" => "guardian",
		"secure" => "tls",
		"port" => 587,
		"fromaddress" => "guardian@thral.de",
		"fromname" => "Wachverwaltung Feuerwehr Landshut"
	),
	"urls" => array (
		"baseUrl" => "http://192.168.11.11/guardian/public_html"
	),
	"paths" => array (
		"resources" => $_SERVER ['DOCUMENT_ROOT'] . "/guardian/resources",
		"images" => array (
			"content" => $_SERVER ["DOCUMENT_ROOT"] . "/guardian/public_html/images/content",
			"layout" => $_SERVER ["DOCUMENT_ROOT"] . "/guardian/public_html/images/layout"
		)
	),
	"settings" => array (
		"selfregistration" => true,			//enables self registration of managers
		"autoadmin" => true,					//manager is always admin
        "reportfunction" => true,				//enalbes function to create event report
        
		"enginemgrmailonsubscription" => true,	//Send mail to all managers of the users engine  on subscription
		"creatormailonsubscription" => true,	//Send mail to creator of the event (if event is full, a mail is always sent)
		"usermailonsubscription" => true, 		//Send mail to subscribing user on subscribing a event
	),
    "formats" => array (
        "date" => "d.m.Y",
        "time" => "H:i"
    )
);

defined ( "LIBRARY_PATH" ) or define ( "LIBRARY_PATH", realpath ( dirname ( __FILE__ ) . '/library' ) );

defined ( "TEMPLATES_PATH" ) or define ( "TEMPLATES_PATH", realpath ( dirname ( __FILE__ ) . '/templates' ) );

/*
 * Error reporting.
 */
ini_set ( "error_reporting", "true" );
error_reporting ( E_ALL | E_STRCT );

?>