# Libre Translate Integration
Place this app in **nextcloud/apps/**

## Configure

Configuration is done using your server's CLI. These following configuration values are available:

- `host`: **required** URL of (your) LibreTranslate instance (without trailing `/`)
- `port`: _optional_ Needed if you are **not** running LibreTranslate on the default http port (http 80 or https 443). E.g. if it is running on a custom port (or LT's default port 5000) (defaults to _`null`_)
- `apikey`: _optional_ Needed if the LT instance requires an api key (defaults to _`null`_)
- `from_lang`: _optional_ default language key you want to translate from (defaults to _en_)
- `to_lang`: _optional_ default language key of your destination language (defaults to _de_)


### Example:
```bash
occ config:app:set integration_libretranslate host --value="https://cloud.your-domain.tld"
occ config:app:set integration_libretranslate port --value="5000"
occ config:app:set integration_libretranslate apikey --value="<API_KEY>"
occ config:app:set integration_libretranslate from_lang --value="en"
occ config:app:set integration_libretranslate to_lang --value="de"
```

## Building the app

The app can be built by using the provided Makefile by running:

    make

This requires the following things to be present:
* make
* which
* tar: for building the archive
* curl: used if phpunit and composer are not installed to fetch them from the web

The make command will install or update Composer dependencies 

## Publish to App Store

First get an account for the [App Store](http://apps.nextcloud.com/) then run:

    make && make appstore

The archive is located in build/artifacts/appstore and can then be uploaded to the App Store.

## Running tests
You can use the provided Makefile to run all tests by using:

    make test

This will run the PHP unit and integration tests and if a package.json is present in the **js/** folder will execute **npm run test**

Of course you can also install [PHPUnit](http://phpunit.de/getting-started.html) and use the configurations directly:

    phpunit -c phpunit.xml

or:

    phpunit -c phpunit.integration.xml

for integration tests
