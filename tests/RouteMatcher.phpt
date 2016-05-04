<?php

use Tester\Assert;
use AlexLawford\RouteMatcher;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

// Apply keys from one array to another
Assert::same(['name' => 'alex', 'age' => 31], RouteMatcher\Match::applyKeys(['name', 'age'], ['alex', 31]));

// ...Even if they are different lengths
Assert::same(['name' => 'alex', 'age' => 31], RouteMatcher\Match::applyKeys(['name', 'age', 'occupation'], ['alex', 31]));

// Check if an array is associative
Assert::true(RouteMatcher\Match::isAssociative(['name' => 'alex']));
Assert::false(RouteMatcher\Match::isAssociative(['alex']));

// Bookending
Assert::same('~^a/?$~', RouteMatcher\Match::bookEnd('a'));
Assert::same('~^b/?$~', RouteMatcher\Match::bookEnd('b/'));

// Straight matching
Assert::same(['uri_string' => 'eat/pizza'], RouteMatcher\Match::route('eat/pizza', 'eat/pizza'));

// Straight matching with trailing slash ON ROUTE
Assert::same(['uri_string' => 'eat/pizza'], RouteMatcher\Match::route('eat/pizza', 'eat/pizza/'));

// Straight matching with trailing slash ON URI
Assert::same(['uri_string' => 'eat/pizza/'], RouteMatcher\Match::route('eat/pizza/', 'eat/pizza'));

// Wildcards
Assert::same(['uri_string' => 'users/alex', 'name' => 'alex'], RouteMatcher\Match::route('users/alex', 'users/alpha:name'));
Assert::same(['uri_string' => 'users/21', 'age' => '21'], RouteMatcher\Match::route('users/21', 'users/number:age'));
Assert::same(['uri_string' => 'pizza/big22', 'flavour' => 'big22'], RouteMatcher\Match::route('pizza/big22', 'pizza/string:flavour'));

// Straight NOT matching
Assert::same([], RouteMatcher\Match::route('users/21', 'pizza/isGood'));

// Wildcards NOT matching
Assert::same([], RouteMatcher\Match::route('users/alex31', 'users/alpha:name'));
Assert::same([], RouteMatcher\Match::route('users/hey', 'users/number:age'));
Assert::same([], RouteMatcher\Match::route('pizza/big22!!', 'pizza/string:flavour'));

// Multiple Wildcards
Assert::same([
    'uri_string' => 'users/alex/31',
    'name' => 'alex',
    'age' => '31'
], RouteMatcher\Match::route('users/alex/31', 'users/alpha:name/number:age'));

// No results if unknown wildcards are used
Assert::same([], RouteMatcher\Match::route('users/alien:name', 'users/alien:name'));