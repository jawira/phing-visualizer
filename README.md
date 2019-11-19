Phing visualizer
================

**Phing visualizer** generates a graphical representation of your 
[Phing](https://www.phing.info/)'s buildfile.

>💡 Important: `phing-visualizer` has been ported to Phing as `VisualizerTask`.  
>You can use `<visualizer/>` since [Phing 3.0.0-alpha3][].

[![Latest Stable Version](https://poser.pugx.org/jawira/phing-visualizer/v/stable)](https://packagist.org/packages/jawira/phing-visualizer)
[![Build Status](https://www.travis-ci.org/jawira/phing-visualizer.svg?branch=develop)](https://www.travis-ci.org/jawira/phing-visualizer)
[![Total Downloads](https://poser.pugx.org/jawira/phing-visualizer/downloads)](https://packagist.org/packages/jawira/phing-visualizer)
[![Maintainability](https://api.codeclimate.com/v1/badges/fc981c0f860275c450be/maintainability)](https://codeclimate.com/github/jawira/phing-visualizer/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/fc981c0f860275c450be/test_coverage)](https://codeclimate.com/github/jawira/phing-visualizer/test_coverage)
[![composer.lock](https://poser.pugx.org/jawira/phing-visualizer/composerlock)](https://packagist.org/packages/jawira/phing-visualizer)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)
[![License](https://poser.pugx.org/jawira/phing-visualizer/license)](https://packagist.org/packages/jawira/phing-visualizer)
[![Issues](https://img.shields.io/github/issues/jawira/phing-visualizer.svg?label=HuBoard&color=694DC2)](https://huboard.com/jawira/phing-visualizer)

With **phing-visualizer** You can go from this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<project name="My Phing's buildfile" default="test">

    <target name="test" depends="test:phpunit, test:composer">
        <phingcall target="test:notify"/>
    </target>

    <target name="test:composer">
        <composer>
            <arg line="validate --strict --no-check-lock"/>
        </composer>
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

![Phing visualizer demo](resources/readme/demo.png)

**Phing visualizer** is able to represent:

- Target's depends
- RunTargetTask
- PhingCallTask
- ForeachTask

Usage
-----

Create your diagram using the command line, some examples:

```console
$ vendor/bin/phing-visualizer
```

```console
$ vendor/bin/phing-visualizer -i build.xml -f svg
```

```console
$ vendor/bin/phing-visualizer --input /my/location/build.xml --format svg
```

```console
$ vendor/bin/phing-visualizer -i /my/location/build.xml -f png  -o /another/location/ 
```

Options
-------

| Option                | Description                                   | Default value     |
| --------------------- | --------------------------------------------- | ----------------- | 
| `-i` or `--input`     | Phing's buildfile location                    | build.xml         |
| `-o` or `--output`    | Dir or file location                          | Same as `--input` |
| `-f` or `--format`    | Diagram format (`png`, `svg`, `eps` or `puml`)| `png`             |
| `-h` or `--help`      | Help                                          |                   |

Installing
----------

```console
$ composer require jawira/phing-visualizer
```

Requirements
------------

- SimpleXML extension
- XSL extension
- `allow_url_fopen = On;` (on `php.ini` file)

More examples
-------------

[![ucenter](resources/readme/ucenter.png)](https://gist.github.com/leric/1216551)

[![App.EduResourceCenter](resources/readme/edu-resource-center.png)](https://gist.github.com/melin/fa4818acc9fd55666b77)

[![Enom Pro!](resources/readme/enom-pro.png)](https://gist.github.com/bobbravo2/0fb3eef82c9c5be60415df61c01e8fd4)

[![Bitpay Magento2 plugin](resources/readme/bitpay-magento.png)](https://github.com/bitpay/magento2-plugin/blob/master/build.xml)

Contributing
------------

If you liked this project, ⭐ star it on [GitHub].

License
-------

This project is under the [GNU GPLv3 license](./LICENSE).

***

Packages from jawira
--------------------

<dl>

<dt><a href="https://packagist.org/packages/jawira/phing-visualizer-gui">jawira/phing-visualizer-gui</a> (library)</dt>
<dd>GUI for jawira/phing-visualizer.</dd>

<dt><a href="https://packagist.org/packages/jawira/phing-open-task">jawira/phing-open-task</a> (library)</dt>
<dd>Phing task to open files, directories, and URLs with your favorite software.</dd>

<dt><a href="https://packagist.org/packages/jawira/process-maker">jawira/process-maker</a> (project)</dt>
<dd>Easily install and try ProcessMaker using Docker Compose.</dd>

<dt><a href="https://packagist.org/packages/jawira/">more...</a></dt>

</dl>


[GitHub]: https://github.com/jawira/phing-visualizer
[Phing 3.0.0-alpha3]: https://github.com/phingofficial/phing/releases/tag/3.0.0-alpha3
