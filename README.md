[![Build Status](https://travis-ci.org/cirrusidentity/simplesamlphp-module-cirrusexample.svg?branch=master)](https://travis-ci.org/cirrusidentity/simplesamlphp-module-cirrusexample)
# simplesamlphp-module-cirrusexample

Examples of doing things in an SSP module


# Mocking/Stubs/Doubles

One challenge of unit testing SSP related code is that there can be a lot of static calls, some of which `exit` the program.
Other components may use a class instance but don't support dependency injection so you can't isolate the
class under test from other components.
[Aspect Mock](https://github.com/Codeception/AspectMock) allows you to stub/mock the behavior
of static methods and of calls to a class instance.

The most important configuration steps are in `bootstrap.php`. We must define the AspectMock `kernel`
and tell it which classes are eligible for mocking/stubbing (the `includePaths` section). AspectMock
 seems to have trouble with SSP's custom naming scheme for module classes (`sspmod_MODULENAME_etc`) and you
 may need to add those classes explicitly via `loadFile`

# Autoloading + Enable file

During tests the SSP autoloader needs to know about your module.  It must be in 
`vendor/simplesamlphp/simplesamlphp/modules/MODULE_NAME` so 
the `tests/bootstrap.php` symlinks the project into the modules directory.

There must also be an `enable` file in the 
root of the project for the SSP autoloader to load the classes.  `touch enable`