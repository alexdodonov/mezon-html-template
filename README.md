# Application classes [![Build Status](https://travis-ci.com/alexdodonov/mezon-html-template.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-html-template) [![codecov](https://codecov.io/gh/alexdodonov/mezon-html-template/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-html-template)

## Installation

Just type

```
composer require mezon/html-template
```

## Usage

First of all you need to create object

```PHP
$template = new \Mezon\HtmlTemplate\HtmlTemplate('./main-template/');
```

This code assumes that you have all template resources in the directory './main-template/'