# Route Matcher

## Introduction

Route matcher provides a function for checking a string against a "pretty" route. An associative array is then returned with all the matches. 

Routes look like this:

    'users/alpha:name/number:age'

If checked against the following string:

    'users/alex/31'

It would return this array:

    Array(
        'uri_string' => 'users/alex/31',
        'name' => 'alex',
        'age' => '31',
    )

## Usage

### Basic usage

Install via composer:

    composer require AlexLawford/RouteMatcher

Use as a static method (both arguments should be strings):

    AlexLawford\RouteMatcher\Match::route('actual/uri', 'my/route')

If there are matches, the corresponding array (see above) will be returned. If there are no matches, it will return an empty array. 

### Wildcards

You can match three kinds of wildcard in your routes:

    scores/number:score/
    users/alpha:name/
    blogs/string:title/

- Numbers matches digits 0-9 of any length.
- Alpha matches A-Z, a-z of any length.
- Strings match A-Z, a-z, 0-9, hyphens, and underscores.

You can, of course, use multiple wildcards within the same route (as above):

   users/alpha:name/number:age/
   
  
