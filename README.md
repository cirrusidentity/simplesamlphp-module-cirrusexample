# simplesamlphp-module-cirrusexample
Examples of doing things in an SSP module




# Mocking/Stubs/Doubles

TODO: document Aspect Mock

# Autoloading + Enable file

During tests the SSP autoloader needs to know about your module.  It must be in 
`vendor/simplesamlphp/simplesamlphp/modules/MODULE_NAME` so 
the `tests/bootstrap.php` symlinks the project into the modules directory.

There must also be an `enable` file in the 
root of the project for the SSP autoloader to load the classes.  `touch enable`