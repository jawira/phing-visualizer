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
$ php bin/phing-visualizer -i ./path/to/build.xml
```

```bash
$ php bin/phing-visualizer -i /my/location/build.xml -o /my/location/ -f png
```

Executable options

| Option                | Description                               | Default           |
| --------------------- | ----------------------------------------- | ----------------- |
| `--input` or `-i`     | Phing's buildfile location                |                   |
| `--output` or `-o`    | Dir or file location                      | Same as `--input` |
| `--format` or `-f`    | Diagram format (`png`, `svg` or `puml`)   | `png`             |


Contributing
------------

To contribute to this project please read [CONTRIBUTING.md](./CONTRIBUTING.md) 
first.


License
-------

This project is under the [GNU GPLv3 license](./LICENSE).
