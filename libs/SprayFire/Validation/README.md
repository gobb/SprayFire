## SprayFire\Validation

**Dependencies:**

- SprayFire\Validation\Check
- SprayFire\Validation\Result
- SprayFire\Object

The Validation module allows developers to ensure that a piece of data matches a specific format, type or convention for what is considered valid for that particular piece of data. It does not, in any way, manipulate or change the data, it simply let's you know if it is valid or not. The Validation module is one of the more complex modules, although it is still easy to use, and has a few different parts to it. Check out the rest of this Module's docs then head over to take a look at the Validation\Check Module and the Validation\Result Module.

> Right now this module is in a very volatile state, it is highly likely to change in a way that breaks v0.1.0a functionality in future versions.

### SprayFire\Validation\Validator

The Validator doesn't hold algorithms to check the validity for a set of data. Rather it is the process in which a set of data is checked against a set of Rules for that set of data. In v0.1.0a the Validator only works on a set of data and the rules for that data set must be passed when validation occurs. Some changes are planned for this module in v0.2.0a that will add to or change this behavior.

### SprayFire\Validation\Rules

The Rules hold a series Validation\Check\Check objects that should be ran against a field in the data set. You can think of Rules as a "container of chains", checks are ran against a field in the order they are added to the chain and you should be able to break out of a field's chain anytime you want. Each field is its own chain, this is important to remember.

> Currently there is a design flaw in `Rules::getChecks($field)` in that the Validator instance must be aware of the arbitrary internal structure returned from this method. This is necessary to determine whether or not the check at that point in the chain should break the rest of the chain for that field. In addition it is highly likely that we will need to add more arbitrary structure to the element causing even more coupling between the Rules and the Validator. A better design could include returning a specific interface but that may be increasing object creation for a module already heavy in object creation.

## SprayFire\Validation\FireValidation

**Dependencies:**

- SprayFire\Validation\Check
- SprayFire\Validation\Check\FireCheck
- SprayFire\CoreObject
- SplObjectStorage

### SprayFire\Validation\FireValidation\Rules

We're gonna talk about the Rules implementation first. Ultimately the Validator from a user's perspective is incredibly simple to use once you have the rules set up. Rules are just a series of checks, so we're going to move forward assuming that you have read over the Validation\Check\FireCheck module and know how to use the implemented checks.

We're going to go over a somewhat close to real life example. Ensuring that a string, a `username`, is an alphanumeric string greater than 3 characters and less than 25 characters. We'll use the created Rules in the Validator examples.

```php
<?php

use \SprayFire\Validation\Check\FireCheck as FireCheck;

// First, let's create the Checks we'll need

$Alphanumeric = new FireCheck\Alphanumeric();
$Range = new FireCheck\Range(3, 25);

// you can set your own customizable, templated validation messages here if you wanted
// check out the Validation\Check and Validation\Check\FireCheck README docs for more info

$Rules->addCheck('username', $Alphanumeric);
$Rules->addCheck('username', $Range);

// If you have a lot of checks the Rules object also supports a "fluent" API to
// add checks to specific fields. Following code is equivalent to above

$Rules->forField('username')->add($Alphanumeric)->add($Range);

// Now that we have our Rules setup check the Validator example below to see them get used

?>
```

### SprayFire\Validation\FireValidation\Validator

The Validator, as of v0.1.0a, only checks a set of data for validity. So, even if you're only checking 1 field, you need to pass in an associative array with the key representing the field to check and the value being, well, the value.

```php
<?php

use \SprayFire\Validation\FireValidation as FireValidation;

$data = ['username' => 'Charles'];
// $Rules is same from example above

$Validator = new FireValidation\Validator();

$Results = $Validator->validate($data, $Rules);

// $Results is a Validation\Result\Set that gives detailed information on each check
// ran against each field in the data set. Check out the Validation\Result module for
// more information.

?>
```