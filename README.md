# No framework

**No framework at all!**


## Quick start

``` bash
$ cp docker-compose.override.yml.example docker-compose.override.yml
$ make setup
$ make start
$ make install
```

You should have the app running on localhost, port 8080.


## Recommended development environment

Make sure the following are installed:

- latest [Docker](https://www.docker.com/) (1.13.0 at the moment)
- latest [Docker Compose](https://docs.docker.com/compose/) (1.10.0 at the moment)
- make


## Components

No framework uses the following components:

- Router: [nikic/fast-route](https://github.com/nikic/FastRoute)
- DI Container: [php-di/php-di](https://github.com/PHP-DI/PHP-DI)
- PSR-7: [zendframework/zend-diactoros](https://github.com/zendframework/zend-diactoros)
- PSR-15: [middlewares/*](https://github.com/middlewares)
- Template Engine: [Twig](http://twig.sensiolabs.org/)


## What else?

[Docker](https://www.docker.com/) for development and a production ready Docker image are included. For simplicity, they are both PHP+Apache based images, in production PHP-FPM and Nginx are recommended.

Worth mentioning that 100% of any building, caching happens only at container build time, no issues like [this](http://stackoverflow.com/questions/38777550/recompile-symfony-container-manually).

[Bootstrap 4](https://v4-alpha.getbootstrap.com/) is used as the frontend framework.


## Credits

This skeleton is heavily inspired by @Swader's [nofw](https://github.com/Swader/nofw) and his [The framework is dead, long live the framework](http://2016.websummercamp.com/PHP/The-framework-is-dead-long-live-the-framework) workshop presented at the [Web Summer Camp](http://2016.websummercamp.com) in 2016.

Phil Sturgeon also has an interesting [article](https://philsturgeon.uk/php/2014/01/13/the-framework-is-dead-long-live-the-framework/) about the topic.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
