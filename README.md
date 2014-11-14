MOE Table Generator
===================

Reads in a .moe file provided by a path and creates HTML roll return tables for school types 20 and 21

Setup
-----

run ```composer install```

Include MOETableGenerator and call with the school name and path to a valid .moe file

e.g.

```php
require_once('moe-table-generator/MOETableGenerator.php');
$returnTables = MOETableGenerator\MOETableGenerator::generateTables('My School Name', $filePath);

```

Tables are also created on disk ***in the same directory as the .moe file, overwriting existing tables at that directory***.
