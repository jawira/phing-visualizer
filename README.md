Phing visualizer
================

**Phing visualizer** generates a PlantUML diagram to have a 
graphical representation for Phing's buildfile.

Requirements
------------

* SimpleXML extension
* XSL extension
* `allow_url_fopen = On;` (on `php.ini` file)

Usage
-----

### `Diagram` class

```php
<?php
include 'vendor/autoload.php';

$intput = '/my/location/buildfile.xml'; // Input buildfile
$output = '/my/location/';              // Can be a dir or a file location
$format = 'png';                        // png, svg or puml

$diagram = new Jawira\PhingVisualiser\Diagram($input);
$diagram->save($format, $output);
```

### Executable

```bash
$ php bin/phing-visualizer.php -i ./path/to/build.xml
```

```bash
$ php bin/phing-visualizer.php -i /my/location/build.xml -o /my/location/ -f png
```

Executable options

| Option                | Description                               | Default           |
| --------------------- | ----------------------------------------- | ----------------- |
| `--input` or `-i`     | Phing's buildfile location                |                   |
| `--output` or `-o`    | Dir or file location                      | Same as `--input` |
| `--format` or `-f`    | Diagram format (`png`, `svg` or `puml`)   | `png`             |

This project adheres
--------------------

* [Keep a Changelog]
* [pds/skeleton]
* [Semantic Versioning]
* [Contributor Covenant]
* [Git flow]

[Keep a Changelog]: http://keepachangelog.com/en/1.0.0/
[pds/skeleton]: https://github.com/php-pds/skeleton
[Semantic Versioning]: http://semver.org/
[Contributor Covenant]: https://www.contributor-covenant.org/
[Git flow]: https://danielkummer.github.io/git-flow-cheatsheet/
