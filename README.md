# No framework

**An opinionated application skeleton to replace frameworks.**


## Components

No framework uses the following components:

- Router: [nikic/fast-route](https://github.com/nikic/FastRoute)
- DI Container: [php-di/php-di](https://github.com/PHP-DI/PHP-DI)
- PSR-7: [zendframework/zend-diactoros](https://github.com/zendframework/zend-diactoros)
- PSR-15: [middlewares/*](https://github.com/middlewares)
- Template Engine: [Twig](http://twig.sensiolabs.org/)

## What else

[Docker](https://www.docker.com/) for development and a production ready Docker image are included. For simplicity,
they are both PHP+Apache based images, in production PHP-FPM and Nginx are recommended.

[Bootstrap 4](https://v4-alpha.getbootstrap.com/) is used as the frontend framework.


## Recommended development environment

Make sure the following are installed:

- latest [Docker](https://www.docker.com/) (1.13.0 at the moment)
- latest [Docker Compose](https://docs.docker.com/compose/) (1.10.0 at the moment)
- make


## Credits

This skeleton is heavily inspired by @Swader's [nofw](https://github.com/Swader/nofw)
and his [The framework is dead, long live the framework](http://2016.websummercamp.com/PHP/The-framework-is-dead-long-live-the-framework)
workshop presented at the [Web Summer Camp](http://2016.websummercamp.com) in 2016.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
