# pdo-altitude-framework
Simple, hi-level abstraction layer for database manipulation in PHP

####PDO-Altitude is a set of two PHP objects, allowing you to easily make lists of items, and read, create, update, delete items from a database table

Full __documentation and examples__ available here (FR & EN): http://www.altitude.polosson.com

Altitude provides two PHP objects, made to perform common operations on MySQL5 or SQLite3 databases, in a secure way, without having to write SQL requests by hand. These two objects are:

- Object Listing : To retreive a list of data from an SQL table, according to a filtering.
- Object Infos : For "CRUD" operations (Create Read Update Delete) on a single entry of an SQL table.

####It's compatible with MySQL and SQLite, working seamlessly with one or another

##Install

Altitude uses PHP's __PDO extension to work__. So you must ensure of its activation within the `php.ini` file of your server.

Just copy the two PHP files from this repo's `classes/` folder to your project.

Then, Altitude needs _at least_ those __5 constants__ to work:

    define ("HOST", "localhost");				// MySQL server host name
    define ("USER", "username");				// SQL user
    define ("PASS", "********");				// Password
    define ("BASE", "database");				// MySQL database name
    define ("DSN",  "mysql:dbname=".BASE.";host=".HOST);
    // Or, for SQLite database:
    // define("DSN","sqlite:path/to/altitude-example.sqlite");

Finally, __include__ the Altitude classes in your PHP script:

    require("classes/Listing.class.php");
    require("classes/Infos.class.php");

_(You may also automatically load these files with an spl_autoload_register() function.)_  
And you're done!

More __documentation and examples__ available here: http://www.altitude.polosson.com/

##How to use: quick start

###Listing

'Listing' is designed to get several entries from a table of the database, using filters, order by, etc.

Here is a small example of simple use. Let's say we've got a table named "users" in our database:

    $l = new Listing();
    $users = $l->getList("users");
    print_r($users);

This will print an array of all users in the table, with their informations in a sub-array.

Now let's say we only want users that are older than 70, but still alive, and order them by name:

    $l = new Listing();
    $l->addFilter("age",   ">", 70);
    $l->addFilter("alive", "=",  1);
    $users = $l->getList("users", "*", "name");
    print_r($users);

'Listing' can also automatically retreive joints and decode JSON, can be very flexible with the use of custom SQL requests, and works for both MySQL and SQLite seamlessly.

For more informations, please read full documentation here: http://www.altitude.polosson.com/doc/en/index.php#listing

###Infos

'Infos' is designed to get one particular entry from a table of the database, but also add, update or delete it.

Here is a small example of simple use of getting an item. Let's say we want the user with ID #3 in table "users":

    $i = new Infos("users");
    $i->loadInfos("id", "3");
    $user = $i->getManyInfos();
    print_r($user);

This will print an array which contains all informations of the user #3.

Now let's update our #3 user's pseudo:

    $i = new Infos("users");
    $i->loadInfos("id", 3);
    $i->setInfo("pseudo", "Jackie");
    $i->save();

Note that, to add a user in the table, you just need to ommit the `$i->loadInfos()` line. If no item loaded, Altitude Infos object will automatically create it (but you'll need to add some mandatory informations, according to your table structure).

And, to delete this user:

    $i = new Infos("users");
    $i->loadInfos("id", 3);
    $i->delete();

For more informations, please read full documentation here: http://www.altitude.polosson.com/doc/en/index.php#infos

Hope you'll find it useful!!
