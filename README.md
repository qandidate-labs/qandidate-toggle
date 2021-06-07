Toggle
======

Feature toggling for PHP.

![build status](https://github.com/qandidate-labs/qandidate-toggle/actions/workflows/ci.yml/badge.svg)

## About

Read our blog post series about this repository at:
- http://labs.qandidate.com/blog/2014/08/18/a-new-feature-toggling-library-for-php/
- http://labs.qandidate.com/blog/2014/08/19/open-sourcing-our-feature-toggle-api-and-ui/

## Installation

Add the library to your project:

```
$ composer require qandidate/toggle ^1.0
```

## How to use

In the examples folder you'll see the basic usage of the Toggle library.

Basically you create a collection with features, optionally with conditions.
Based on a given Context, the manager will tell you if a feature is active or not.


## Tests

To run all the tests you'll need to have redis installed (redis tests wil be skipped when not available).

Running the tests:

```
$ make test
```

## License

MIT, see LICENSE.
