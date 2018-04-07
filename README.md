## Installation

### requiremants

To run the code you must have php 7.1.* installed and mysql db. Also you need composer.

Here's requirements to run the docs https://docs.phpdoc.org/getting-started/installing.html#system-requirements

### how2install

Clone repo via `git clone https://github.com/MrFimaCake/ev-comm-test.git` then go to the folder.
Then run `php env-setup.php` and in created `.env` file set the db confing variables.
Run `composer install` to install dependecies. 

### Commands

To run the tests run the `vendor/bin/phpunit --bootstrap vendor/autoload.php tests` from the app root.

To generate documentation run `vendor/phpdocumentor/phpdocumentor/bin/phpdoc -d ./src -t docs/api`.
