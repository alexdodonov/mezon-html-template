# HTML template class
[![Build Status](https://travis-ci.com/alexdodonov/mezon-html-template.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-html-template) [![codecov](https://codecov.io/gh/alexdodonov/mezon-html-template/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-html-template) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-html-template/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-html-template/?branch=master)

This class provides routine for HTML pages generation. Works fast and quite simple in use.

It is a part of [Mezon Framework](https://github.com/alexdodonov/mezon) and used in the [Application class](https://github.com/alexdodonov/mezon-common-application)

# Learn more

More information can be found here:

[Twitter](https://twitter.com/mezonphp)

[dev.to](https://dev.to/alexdodonov)

# I'll be very glad if you'll press "STAR" button )

## Installation

Just type

```
composer require mezon/html-template
```

## Usage

### Paths to template files

First of all you need to create object.

```PHP
use Mezon\HtmlTemplate\HtmlTemplate;

$template = new HtmlTemplate('./main-template/');
```

This code assumes that you have all template resources in the directory `./main-template/`.

But you can also specify a list of paths, and while template compilation they all will be scanned for template files.

```php
$template = new HtmlTemplate(['./main-template/', './extra-files/res/']);
```

No need to specify all paths in the constructor. You can do it later with methods:

```php
$template = new HtmlTemplate('./main-template/');
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

### Setup layout

You can have different layouts for your pages:

```php
$template = new HtmlTemplate(%template-sources%, 'index');
// or 'form' instead of 'index', or '404', or '403' etc.
```

Layout will let you to define different templates for your different purposes.

You can create in your template as much layouts as you need. Just add files:

```
%template-sources%/Res/Templates/infex.html
%template-sources%/Res/Templates/form.html
%template-sources%/Res/Templates/404.html
%template-sources%/Res/Templates/403.html
// and so on
```

### Setup blocks

After the layout was setup you can pass blocks to the template.

It will allow you to put different content in your layout.

For example:

```php
$template->addBlock('hello-world');
```
That means that `HtmlTemplate` class will get the file `%template-sources%/Res/Blocks/%block-name%.tpl` and put instead the placeholder `{%block-name%}` within your layout.

But you can place the block in any placeholder you need:

```php
HtmlTemplate::setPageVarFromBlock(string $var, string $blockName);
// here is $var is the name if your placeholder within the layout
// and $blockName is %template-sources%/Res/Blocks/$blockName.tpl
```

If you have `$var=foo` then your placeholder must be {foo}

### Setting vars

You can also pass almost any data to the template

```php
// here is $value is a scalar value or object wich implements __toString method
// and $var is a name of your placeholder within the layout
setPageVar(string $var, $value): void;

// this method can be used if you need to place file's $path content in your layout
setPageVarFromFile(string $var, string $path): void;

// you can set multyple vars with one call
// here $vars is an assoc array
setPageVars(array $vars): void;
```

### Compile template

Just call

```php
compile(): string
```

The method will return a compiled page wich you can send to user of your application.