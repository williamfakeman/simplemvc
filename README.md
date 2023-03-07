A simple MVC implementation in pure PHP.

- does not use any libraries;
- web access only through public/ folder;
- supports routes with dynamic URL variables;
- can work in any subfolder in the document root folder;
- does not use global variables and constants except for the ROOT constant, which points to a subfolder in the document root;
- uses pure PHP templates for views;
- uses Composer as a PSR-4 class autoloader;
- has its own ORM with binding types, tested with MySQL, should work with PostgreSQL (you can create tables manually);
- supports basic session authentication (use admin:123 for testing);
- the core contains less than 600 lines of well-documented code in 5 files;
- covered with unit tests (PHPUnit 10);
- tested on PHP 8.2;
- created as a result of test work for employment.

Usage:
1. Change database credentails in src/Model.php
2. Manually create tables and indexes in the database.
3. Define routes in src/App.php.
4. Create a controller files, see src/Controllers/TaskController.php for an example.
5. Create a model files, see src/Models/TaskModel.php for an example.
6. Create View Files in the views/ folder.
7. Modify any files as you wish.
8. ?????
9. PROFIT
