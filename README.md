# Fork 3.6 to 3.9 Upgrade

The [Fork CMS](https://github.com/forkcms/forkcms) upgrade from 3.6 to 3.7 migrated to Symfony, namespaces, DI, and assorted modern features.

Unfortunately this rendered existing installations of fork invalid.

This script performs an automated upgrade of the contents of the database from 3.6 to 3.7 compatiblity.

This is very much a 'use at your own risk' script, and has only actually been used on a 3.6 to 3.9 upgrade.

## Usage

Use the new fork installer to configure a fresh copy of fork 3.9.

Copy your fork 3.6 database.  Edit the 36_to_39.php script to enter the credentials for your copied database.

Run the 36_to_39.php script: `php 36_to_39.php > upgrade.sql`

Review the upgrade.sql file and if all looks well, run it (`mysql copied_db < upgrade.sql`)

Edit your fork 3.9 installations parameters.yml file to point at your copied database.  

Delete all entries in the fork 3.9 frontend and backend caches. Login to the backend and edit and save a page.

Test the site. Cross your fingers. Submit a pull request with whatever changes you had to make!

_nosnickid_
