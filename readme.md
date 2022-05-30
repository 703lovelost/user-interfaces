Project name: User Interfaces
Author: Aleksey Spirkin - https://github.com/703lovelost

This application was made as the job test for Junior PHP developer's vacancy.
No frameworks were used throughout the work.
The design might be primitive, as the main focus was on functions' work.

## The application contains:

1. Authorization form, leading to the available interface, depending on your role.
2. Registration form - signing up this way will give you a 'client' role automatically.
3. Client Interface - actually, nothing's there except log out link.
4. Admin Interface:
4.1. The entire list of users currently registered, split into pages. Each page contains 5 users from the list, sorted in ascending order.
4.2. Each user can be edited and deleted:
4.2.1. Editing form shows the current values of user's login, first name, last name, sex, birth date and role.
Besides other fields, administrator can also change user's password. If password's field is left blank, password will stay untouched.
4.3. Administrator can add a new user, by clicking on the 'Add user' button and filling the necessary fields.
5. Every type of user can log out.
6. All the error messages and notifications are shown on top of the page.

## How to install:

Instruction is written taking into account the use of Open Server (https://ospanel.io/), as the application's functionality was checked using it.

1. Move the 'userinterfaces' folder to the directory '...\OpenServer\domains\';
2. Double-click on '...\OpenServer\Open Server.exe' to start the server's work.
3. Find Open Server's icon in the bottom-left corner, open the settings and check if you have the next values in the 'Modules' section:
- HTTP: Apache_2.4-PHP_7.2-7.4+Nginx_1.17
- PHP: PHP_7.4
- MySQL / MariaDB - MySQL-8.0
4. Open phpMyAdmin (http://127.0.0.1/openserver/phpmyadmin/index.php) and create database 'userinterfaces_db';
5. Log in with login 'root' and password 'root';
6. By clicking on 'Import' section, upload file 'userinterfaces_db.sql', coming in addition to the 'userinterfaces' folder;
7. Find Open Server's icon in the bottom-left corner, go to My Projects and click on 'userinterfaces'.

Use login 'admin' and password 'admin' to check how admin's interface works. You can also make some new users with admin privileges using it.

Enjoy!
