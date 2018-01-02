Phing visualizer
================

**Phing visualizer** generates a PlantUML diagram to have a 
graphical representation for Phing's buildfile.

Requirements
------------

* SimpleXML extension
* XSL extension
* `allow_url_fopen = On;` (on `php.ini` file)

Use
---

You can use executable to generate 

```bash
$ php bin/phing-visualizer.php -i ./path/to/build.xml -o ./path/to/diagram.png -f png
```

```bash
$ php bin/phing-visualizer.php -i ./path/to/build.xml
```


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
