<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

$util = array (
		
		"head" => "Lieber Nutzer, \n\n"
		
);

$bodies = array (
		
		"event_insert" => $util["head"] . "eine neue Wache wurde eingestellt: \n\n",
		
		"event_publish" => $util["head"] . "eine neue Wache wurde veröffentlicht: \n\n",	
		
		"event_delete" => $util["head"] . "eine Wache, bei der Sie sich eingetragen haben, wurde abgesagt: \n\n",
		
		"event_subscribe" => $util["head"] . "sie haben sich in eine Wache eingeschrieben: \n\n",
		
		"event_subscribe_manager" => $util["head"] . "jemand aus Ihrem Zug hat sich in eine Wache eingeschrieben: \n\n",
		
		"event_full" => $util["head"] . "eine von Ihnen erstellte Wache ist voll belegt: \n\n",
		
		"event_subscribe_engine" => $util["head"] . "jemand hat sich in eine von Ihnen erstellte Wache eingeschrieben: \n\n",
		
		"event_unscribe" => $util["head"] . "sie wurden durch den Wachbeauftragten von der Wache entfernt: \n\n",
		
		"event_unscribe_engine" => $util["head"] . "jemand aus Ihrem Zug wurde durch den Wachbeauftragten von der Wache entfernt:  \n\n",
		
		
		"manager_add" => $util["head"] . "für Sie wurde ein Zugang als Wachbeauftragter angelegt:",
		"login" => "\n\nLogin: ",
		"password" => "\n\nPasswort: ",
		"manager_add2" => "\n\nSie können sich jetzt im Portal unter " . $config ["urls"] ["baseUrl"] . " anmelden.",
		
		
		"manager_reset_password" => $util["head"] . "ihr Passwort wurde zurückgesetzt:",
		"manager_reset_password2" => "\n\n Sie können es im Portal unter " . $config ["urls"] ["baseUrl"] . " in ihr Wunschkennwort ändern.",
		
);