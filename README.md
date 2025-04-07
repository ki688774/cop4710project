## Setup Instructions
Before you do anything, make sure to import "import_this.sql". To do so, open up mySQL. Then, run the command "source [path to import_this.sql]".



## General Responses
If the API endpoint comes to its natural end, then it will return a single json object named "results". Usually, this is a message saying that the operation completed successfully. However, the search/get endpoints will instead return the found object(s) as "results". In the case of the search endpoints specifically, even if it finds nothing it will return "results" (although it will be an empty array).

If the API endpoint comes to an abnormal end, then it will return a json object named "error", describing what went wrong in an end-user friendly manner. In the case of any errors thrown by a mySQL command, it will also return an extra json object named "my_sql_error" containing the error thrown.



## Common Errors
If you can't get into mySQL, try using "root" as the username and "" as the password (without the quotation marks).

If mySQL refuses to allow the .php files to log in as 'root'@'localhost', create a new user and grant it all permissions to the cop4710project database. Then, change the default credentials in global.php to the new user's credentials.



## Things To Be Aware Of
Students are allowed to be linked to no university (if their email domain is not recognized), but cannot sign up when there is no university. This is because when creating a university and super-admin, the super-admin needs to be created first, then their university, then the two can be linked.

Currently, student accounts get deleted when their corresponding university is deleted. If the university's email domain changes, each student stays linked to the university and their emails do not change. Only new students are affected.

searchLocations.php and getUniversity.php can go through even when not signed in.

global.php is a file containing many functions that are frequently used across the other files. Furthermore, it also contains the log in credentials for mySQL.
