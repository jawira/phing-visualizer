help:				## Help
	@grep -Eh '^[a-zA-Z_-\.]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
.PHONY: help

cc-test-reporter:	## CodeClimate reporter
	wget -O ./cc-test-reporter https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64
	chmod +x ./cc-test-reporter
	./cc-test-reporter -v

/usr/local/bin/phive:
	wget -O phive.phar https://phar.io/releases/phive.phar
	wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
	gpg --keyserver pool.sks-keyservers.net --recv-keys 0x9D8A98B29B2D5D79
	gpg --verify phive.phar.asc phive.phar
	chmod +x phive.phar
	sudo mv phive.phar /usr/local/bin/phive
	phive --version
