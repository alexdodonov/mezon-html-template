# HTML template class
[![Build Status](https://travis-ci.com/alexdodonov/mezon-html-template.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-html-template) [![codecov](https://codecov.io/gh/alexdodonov/mezon-html-template/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-html-template) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-html-template/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-html-template/?branch=master)

## Installation

Just type

```
composer require mezon/html-template
```

## Usage

First of all you need to create object.

```PHP
$template = new \Mezon\HtmlTemplate\HtmlTemplate('./main-template/');
```

This code assumes that you have all template resources in the directory `./main-template/`.

But you can also specify a list of paths, and while template compilation they all will be scanned for static files.

```php
$template = new \Mezon\HtmlTemplate\HtmlTemplate(['./main-template/', './extra-files/res/']);
```

No need to specify all paths in the constructor. You can do it later with methods:

```php
$template = new \Mezon\HtmlTemplate\HtmlTemplate('./main-template/');
$template->addPaths(['./path1', './path2']);
```

But be carefull if you have static files with the same names on different paths. While compilation the file on the latest added path will be used. It was done so to create a sort of overriding mechanism for template resources.

Or you can completely reset all paths:

```php
$template->setPaths(['./path1', './path2']);
```

And view a list of paths:

```php
var_dump($template->getPaths());
```
