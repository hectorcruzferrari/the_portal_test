# The Portal Test

This is an application which given a input file, generates a output file sorted by the pick_location column.

The purpose of this application is the Job Application of Hector Cruz. 

As the specification of this test said, this is a problem which could be solved in a very simply way, but I've decided to do it in a complex way just to show how I'm used to develop. 
Some practices to highlight are:

* Manage of Laravel Framework
* Use of MVC approach, using File model and the FilesController
* Use of git repositories.
* Know how to use vendor packages (import process)
* Know how to manipulate file using php functions (output process)
* How to create CLI commands from Laravel
* How to create a database. In this particular I must mention this exercise doesn't allow to show my SQL knowledge. I'm able to design a database from scratch and optimise it, and I often prefer put some logic directly on the database using stored procedures rather to use the PHP server for it, it depends the circumstances but I'm used to prefer to do not overload the web server if the process could be expensive in terms of resources. For that reason, I choosed to sort this table using the SQL "orderBy" instead do it on PHP, which it'd be just store it in arrays and iterate on it,  performing any ordering algorithm 


## Installation

1. `git clone https://github.com/hectorcruzferrari/the_portal_test.git`
2. `cd the_portal_test` or the project folder name.
3. `composer update`
4. `composer dump-autoload`
5. Create the database. It could be according to the config file (.env) named "the_portal_test"
6. Modify the .env file according the database connection settings.
7. `php artisan migrate:install`
8. `php artisan migrate`

## How to use it

Simply you need run the command as below indicating the input file and an optional output parameter to specify the output filename.

If the input file is inside the root project forlder

`php artisan picking "input.csv"`

or specify the path if it's needed

`php artisan picking "/path/to/file/input.csv"` for linux
`php artisan picking "C:\path\to\file\input.csv"` for windows

You can specify the output file using the `--output=` option. 

`php artisan picking "input.csv" --output="output.csv"`


