# Enrichment Progress
This is a package that contains custom data type that allows to monitor enrichment progress of objects.

It calculates the progress based on mandatory fields and displays it on top of the object editor:

![editor](docs/object.png)

and in the grid:

![grid](docs/grid.png)


**Table of Contents**

- [Enrichment Progress](#enrichment-progress)
	- [Compatibility](#compatibility)
	- [Installation](#installation)
	- [Contributing](#contributing)
    - [Licensing](#licensing)
    - [Standards & Code Quality](#standards-code-quality)
    - [About Authors](#about-authors)
    
## Compatibility
Pimcore 5.5 and higher.

## Installation
```
composer require divante-ltd/pimcore-enrichment-progress
```

Enable the Bundle:
```bash
./bin/console pimcore:bundle:enable DivanteEnrichmentProgressBundle
```

After those steps, you should find a new data type called "Enrichment Progress" in the classes editor under Other section.

## Testing
Unit Tests:
```bash
PIMCORE_TEST_DB_DSN="mysql://username:password@localhost/pimcore_test" \
    vendor/bin/phpunit
```

Functional Tests:
```bash
PIMCORE_TEST_DB_DSN="mysql://username:password@localhost/pimcore_test" \
    vendor/bin/codecept run -c tests/codeception.dist.yml
```

## Contributing
If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.
