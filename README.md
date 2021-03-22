# individual-project

Akeeb Saberi (saberia@aston.ac.uk)
Candidate Number: 991554
BSc Digital & Technology Solutions
Aston University

Employee Resource Management System

Deployment Instructions

1)	Install XAMPP Control Panel from: https://www.apachefriends.org/index.html
2)	Once installed, open the XAMPP Control Panel and start both Apache and MySQL modules.
3)	In your browser, go to the following URL: localhost/phpMyAdmin
4)  Select New to create a new database.
5)  In the form, name the database as the following: individual_project_db
6)	Select the ‘Create’ button. This will create an empty database with the name of ‘individual_project_db’.
7)	Select the newly created database and go to the Import tab. Select the ‘Choose file’ button and locate the SQL script called individual_project_db.sql in the Release folder provided (under Database SQL Scripts).
8)	Scroll to the bottom and click ‘GO’. Once completed, the database should now contain a number of tables populated with the dummy data used for both development and unit testing. You can now close this tab.
9)	Open Windows Explorer and navigate to the htdocs folder in your xampp directory.
10)	Copy the contents of the ‘Application Source Code’ folder (from the Release) and paste it into the htdocs folder. This should result in the ‘individual-project’ folder residing in the htdocs folder in the xampp directory.
11)	In your browser, go to the following URL: localhost/individual-project
You should be able to access the web application and login with credentials.

Note: please ensure that you name the database exactly as shown in the steps above, otherwise the application code will not recognise the database
