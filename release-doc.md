******************************
Release Documentation
******************************

##### V1.1

Added export function for event data (administration only)

##### V1.0.1

Updated calender file export


##### V1.0.0

RewriteRule ^html/guardian/reports/export/?$ 	/guardian/html/guardianapp/report_export.php

.htaccess 
	Change report/new from event_report to report_edit
	reports/new/([^/]*)?$ to report_edit.php?event=$1
	reports/([^/]*)/edit?$ 	/guardian/html/guardianapp/report_edit.php?id=$1
	

ALTER TABLE report ADD event CHAR(36) NULL AFTER uuid; 
ALTER TABLE report ADD managerApproved BOOLEAN NOT NULL AFTER emsEntry; 



******************************
New installation
******************************

Clone repository in webserver folder (e.g. /var/www/)

Replace and adapt htaccess and config samples

Create new database

apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget

Install nodejs

 	curl -sL https://deb.nodesource.com/setup_12.x | bash -
	apt-get install nodejs
	node -v
	npm -v


Change nodejs path in config file
Run in folder {application}/resources/library/puppeteer/
	npm init 
	(set name to fflaintranet)
	npm i --save puppeteer

Create folder "reports" and check access/write rights
