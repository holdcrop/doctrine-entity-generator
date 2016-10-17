# Doctrine Entity Generator

Tool to build Doctrine compatible Entities and Repositories, from the database.
The tables and their structure are retrieved from the database using PDO, and then the data is used to build the basic entities and repositories needed by Doctrine. No entity relationships are added. 

## Instructions

In the *config/config.php* file:
* Add your database connection details to the _connection_ array
* Specify the output directory, _dir_, for both your entities and repositories to be written to
* Add the _namespace_ to be used in the entitie and repositories
* Add the _extends_ and _use_ options if any to be added to the entity and repository classes.

To generate the entities, run the following:
```
$generator = new \DoctrineEntityGenerator\Generate();
$generator->generate();
```

## Notes
* This is not a complete tool. So far, it has only been configured and tested against a MySql database.
* All possible SQL types have not been accounted for, only most of the simple types.

Any feedback or asistance welcome! Enjoy!
            