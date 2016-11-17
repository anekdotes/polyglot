# Anekdotes Polyglot

[![Latest Stable Version](https://poser.pugx.org/anekdotes/polyglot/v/stable)](https://packagist.org/packages/anekdotes/polyglot)
[![Build Status](https://travis-ci.org/anekdotes/polyglot.svg?branch=master)](https://travis-ci.org/anekdotes/polyglot)
[![codecov.io](https://codecov.io/gh/anekdotes/polyglot/coverage.svg)](https://codecov.io/gh/anekdotes/polyglot?branch=master)
[![StyleCI](https://styleci.io/repos/63600389/shield?style=flat)](https://styleci.io/repos/63600389)
[![License](https://poser.pugx.org/anekdotes/polyglot/license)](https://packagist.org/packages/anekdotes/polyglot)
[![Total Downloads](https://poser.pugx.org/anekdotes/polyglot/downloads)](https://packagist.org/packages/anekdotes/polyglot)

Abstract model that eases the localization of Illuminate model.

## Installation

Install via composer into your project:

    composer require anekdotes/polyglot

## Usage

Extends the model you wish to translate with the class

```php
class Test extends TranslatedModel
```

Add the desired translated columns to the polyglot array (locale has to be there)

```php
protected $polyglot = ['locale', 'title', 'description', 'slug'];
```

Don't forget to also add the translated columns to the fillable array

```php
protected $fillable = ['rank', 'locale', 'title', 'description', 'slug'];
```

Make a new file preferably <name>Lang.php and extends Illuminate Model

```php
class TestLang extends Model
```

Add the translated columns to the fillable array and set timestamps to false

```php
protected $fillable = ['locale', 'title', 'description', 'slug'];

public $timestamps = false;
```
