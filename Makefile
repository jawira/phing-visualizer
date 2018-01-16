help:				## Help
	@grep -E '^[a-zA-Z_-\.]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
.PHONY: help

cc-test-reporter:	## CodeClimate reporter
	@wget -O ./cc-test-reporter https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64
	@chmod +x ./cc-test-reporter
	@./cc-test-reporter -v

phing.phar:			## Download Phing
	@wget -O ./phing.phar http://www.phing.info/get/phing-2.16.0.phar
	@chmod +x ./phing.phar
	@./phing.phar -v

phpunit.phar:		## Download PHPUnit
	@wget -O ./phpunit.phar https://phar.phpunit.de/phpunit-6.phar
	@chmod +x ./phpunit.phar
	@./phpunit.phar --version
