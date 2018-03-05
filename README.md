Phing visualizer
================

**Phing visualizer** generates a graphical representation of your Phing's 
buildfile.

[![Latest Stable Version](https://poser.pugx.org/jawira/phing-visualizer/v/stable)](https://packagist.org/packages/jawira/phing-visualizer)
[![License](https://poser.pugx.org/jawira/phing-visualizer/license)](https://packagist.org/packages/jawira/phing-visualizer)
[![composer.lock](https://poser.pugx.org/jawira/phing-visualizer/composerlock)](https://packagist.org/packages/jawira/phing-visualizer)

You can go from this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<project name="My Phing's buildfile" default="test">

    <target name="test" depends="test:phpunit, test:composer">
        <phingcall target="test:notify"/>
    </target>

    <target name="test:composer">
        <exec command="composer validate --strict --no-check-lock" passthru="true"/>
    </target>

    <target name="test:phpunit">
        <exec executable="${phpunit}"/>
        <phingcall target="test:clean"/>
    </target>

    <target name="test:notify">
        <notifysend msg="Everything is OK!"/>
    </target>

    <target name="test:clean">
        <delete dir="${dir.output}" verbose="true"/>
    </target>

    <target name="diagnostics">
        <diagnostics/>
    </target>

</project>
```

To this:

![demo visualization](https://raw.githubusercontent.com/jawira/phing-visualizer/master/resources/readme/demo.png)

**Phing visualizer** is able to represent:

* Target's depends
* PhingCallTask
* ForeachTask

Requirements
------------

* SimpleXML extension
* XSL extension
* `allow_url_fopen = On;` (on `php.ini` file)

Installing
----------

The easiest way to install is with Composer:

```bash
$ composer require jawira/phing-visualizer
```

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
$ vendor/bin/phing-visualizer -i /my/location/build.xml -f svg
```

```bash
$ vendor/bin/phing-visualizer -i /my/location/build.xml -f png  -o /another/location/ 
```

Executable options

| Option                | Description                               | Default           |
| --------------------- | ----------------------------------------- | ----------------- |
| `--input` or `-i`     | Phing's buildfile location                |                   |
| `--output` or `-o`    | Dir or file location                      | Same as `--input` |
| `--format` or `-f`    | Diagram format (`png`, `svg` or `puml`)   |                   |


Contributing
------------

To contribute to this project please read [CONTRIBUTING.md](./CONTRIBUTING.md) 
first.


License
-------

This project is under the [GNU GPLv3 license](./LICENSE).
