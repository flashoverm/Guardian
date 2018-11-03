#guardian

Bugs:

- Date/Time is empty after adding station/vehicle (close popup)
- In Vehicle/Station field: Date, Times are false formated (on mobile)


To Do: 


New feature:

- Public events overview (like event overview)

- Landing Page "intranet.feuerwehr-landshut.de" -> "Wachverwaltung" | "Hydranten"

- Wachhabenden-Link der Bericht mit bekannten Felder ausfüllt

- Save report textfiles on server

- Send HTML mails with infos about events
- Send iCal-File for adding event to calender

- Edit-function for units in event report


Refactoring: 

- rename files and functions in camel case?
- consistent usage of " and '
- methods for updating database structure and content (staffpositions, eventtypes)


Done: 

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
