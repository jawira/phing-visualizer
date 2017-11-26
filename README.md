Phing diagram
=============

**Phing diagram** generates a PlantUML diagram to have a 
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
$ php public/phing-diagram.php -i ./path/to/build.xml -o ./path/to/diagram.png -f png
```


This project adheres
--------------------

* [Keep a Changelog]
* [pds/skeleton]
* [Semantic Versioning]
* [Contributor Covenant]


[Keep a Changelog]: http://keepachangelog.com/en/1.0.0/
[pds/skeleton]: https://github.com/php-pds/skeleton
[Semantic Versioning]: http://semver.org/
[Contributor Covenant]: https://www.contributor-covenant.org/
