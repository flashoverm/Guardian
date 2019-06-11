#guardian

ALTER TABLE `event` ADD `staff_confirmation` BOOLEAN NOT NULL AFTER `published`; 
ALTER TABLE `event` ADD `deleted_by` CHAR(36) NULL AFTER `staff_confirmation`; 
ALTER TABLE `staff` ADD `unconfirmed` BOOLEAN NOT NULL AFTER `user`; 
ALTER TABLE `eventtype` ADD `isseries` BOOLEAN NOT NULL AFTER `type`; 
ALTER TABLE `event` CHANGE `end_time` `end_time` TIME NULL; 
ALTER TABLE `user` ADD `active` BOOLEAN NOT NULL AFTER `ismanager`; 
UPDATE `user` SET active = TRUE

RewriteRule ^html/events/([^/]+)/assign/?([^/]*)/?$ /guardian/html/event_assign.php?id=$1&staffid=$2
RewriteRule ^html/ajax/user/([^/]+)/?$ /guardian/html/ajax/user.php?uuid=$1
RewriteRule ^html/user/?$ /guardian/html/user_overview.php

Bugs:

- On edit, write-lock dataset: https://www.codexpedia.com/php/lock-a-mysql-table-in-php/

- On SQL-Update: Check if condition is fullfilled

To Do:

 Ausführlicher Testbetrieb! 

 Infos mit 2-3 Terminen wegen Vorstellung
 
 Logging
  
New feature:

- Make position available after assigned (Mark as "released")

- Check dates - New event could not be in past, report not in future

- Event import function from excel

- Event Report Export in Invoice-Style for Administration

- Wachhabenden-Link der Bericht mit bekannten Felder ausfüllt (Personal fehlt noch)

Low prio:

- Edit-function for units in event report
	
- Send HTML mails with infos about events


Refactoring: 

- report date format from config, not hard coded
- rename files and functions in camel case?
- consistent usage of " and '
- move javascript in own js-file

Done:
- Added Test-Warning-Text

- E-Mail reminder to assigned engine of the event, not creator
- Admin can block user for event participation 
- Masseneingabe
- Eine Person darf nicht zwei Positionen belegen
- Wachhabenden-Link der Bericht mit bekannten Felder ausfüllt
- Confirmation of attendence of an event by manager of assigned engine
- Wache löschen - Datensatz behalten, markieren, festhalten, wer die Wache gelöscht hat
- Mail, durch selbst druchgeführte Maßnahmen nur optional:
	Person trägt sich ein: Keine Mail an diese Person (mit optionalem Haken)
	Wachhabender trägt ein: Keine Mail an diesen Wachhabenden (mit optionalem Haken)
	usw.
- Autovervollständigen bei "Eintragen" von Personal aus bisherigen Eingaben bzw. MP-Feuerwehr
- Wachbericht erstellt, E-Mail an Geschäftszimmer (Burg, Rathaus, Residenz, Sonstige, Sparkasse)


- Report in detail (not text)
- use path variables for user-, event-id, ...
- Templates für Personal pro Wachtyp
- Geschäftszimmer in Verwaltung ändern


- Event-Detail page: send update/info to subscribers - button (better: automatically of event details change) -> Mail with "event updated" to subscribers ("Personal infomieren") 
- Event-Detail page: Some field editable, especially "comment", other fields eventually


- Landing Page "intranet.feuerwehr-landshut.de" -> "Wachverwaltung" | "Hydranten"
- Manual with i-Symbol in navigation


- Toolstipps for no obvious fields (Zuständiger Löschzug, Veröffentlichen)
- Save reports on server (List view + report page)
- Reminder 10 days before event if not full
		(Cron job 1 am which runs code sniped to fire mails)
- iCal-File download for adding event to calender


- Datepicker in IE 11 does no work


- Switch mail from @thral.de to @feuerwehr-landshut.de
- Dont send mails to deactivated users
- Send mail to manager if event is assigned to engine


- Event overview : Own events | Public events | past events
- Active events: Sort ascending <
- Use case changed: events are not bound to an manager but an engine - manager is creator
- Remove fields "unitname", "km" form modal if "no vehicle"
- assignement of event to other engines is possible

 
- Event-Detail page: move publish button so other side -> accidentally click!
- After event subscription -> Redirect to detail-page (with info about success, or not)
- Event-Subscribe page: Show infos about event and position, i.e. "Eintragen für Theaterwache -> Dienstgrad"


- In Vehicle/Station field: Date, Times are false formated (on mobile)
- Updated report ui: seperate button for station/vehicle
- Public events overview (configurable)
- Date/Time is empty after adding station/vehicle (close popup on "add"!)
- Splitted adding personal on report
- Centered table fields


- "Save to clipboard"-button 
- Improved error handling on sending mails
- Confirm "Veröffentlichen"
- Field "Sonstige Wache" if selected (Create event)


- E-Mail-Betreff: Datum - Wachbeginn - Typ
- Dropdown for event positions


- Sort events descending by date (overview)
- Event Overview: Occupancy 1/3, 2/3 (Color Red/Green)
- Wachbeginn instead of Beginn
- "E-Mail an alle Wachbeauftragen"
- After creating event, redirect to detail page
- "Titel" not required


- Verification dialog for ciritical operations
	(Reset password, delete event, unscribe User)
- Loading Screen while mail sending
- Handling errors, mail exception
- Positions like access database
- Deactivating own account not possible
- Date format in DD.MM.YYYY, Time in format hh:mm (24 hours)
- Call database tables singular
- Add Checkbox "Einsatz durch ILS angelegt"
