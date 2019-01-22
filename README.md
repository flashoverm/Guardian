#guardian

Bugs:

- 

To Do: 

- Manual with ?-Symbol in navigation

- "Working-copy aus Seitentitel entfernen"?

New feature:

- Event-Detail page: send update/info to subscribers - button (better: automatically of event details change) -> Mail with "event updated" to subscribers ("Personal infomieren") 
- Event-Detail page: Some field editable, especially "comment", other fields eventually


- Landing Page "intranet.feuerwehr-landshut.de" -> "Wachverwaltung" | "Hydranten"

- Event import function from excel


Low prio:

- Wachhabenden-Link der Bericht mit bekannten Felder ausfüllt
- Edit-function for units in event report
	
- Send HTML mails with infos about events


Refactoring: 

- use path variables for user-, event-id, ...
- report date format from config, not hard coded
- rename files and functions in camel case?
- consistent usage of " and '
- methods for updating database structure and content (staffpositions, eventtypes)


Done:
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
