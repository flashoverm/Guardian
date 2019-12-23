******************************
Release Documentation
******************************

Config update: Paths
        "reports" => $_SERVER ["DOCUMENT_ROOT"] . "/ffla-intranet/resources/reports/",
        "nodejs" => "D:/runtimes/nodejs/node.exe"
        
htdocs: report/file (2x)



******************************
New installation
******************************

Clone repository in webserver folder (e.g. /var/www/)

Replace and adapt htaccess and config samples

Create new database

apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget

Install nodejs
Change nodejs path in config file
Run in folder {application}/resources/library/puppeteer/
	npm init 
	(set name to fflaintranet)
	npm i --save puppeteer
