Toggle
======

Feature toggling for PHP.

[![Build Status](https://travis-ci.org/qandidate-labs/qandidate-toggle.svg?branch=master)](https://travis-ci.org/qandidate-labs/qandidate-toggle)

## About

Read our blog post series about this repository at:
- http://labs.qandidate.com/blog/2014/08/18/a-new-feature-toggling-library-for-php/
- http://labs.qandidate.com/blog/2014/08/19/open-sourcing-our-feature-toggle-api-and-ui/

## Installation

Install the dependencies with composer:

```
$ composer install
```

## How to use

In the examples folder you'll see the basic usage of the Toggle library.

Basically you create a collection with features, optionally with conditions.
Based on a given Context, the manager will tell you if a feature is active or not.


## Tests

To run the tests you'll need to have redis installed.

Running the tests:

```
$ phpunit
```
## License

MIT, see LICENSE.
