# phackp
PHP Micro Framework, born in PHP7 Age!

# disclaimer

pHackp is actually in alpha stage.
the framework might change in ways that make alpha/beta releases un-compatible with the first stable version in the future.
The framework is actually compatible with PHP7+.

# Ready to start?
https://github.com/yuxblank/phackp/wiki/Get-started

# Introduction
pHackp aim to provide a fast and easy way to build modern and responsive websites and apps.
It provides an Model View Controller structure for organizing your project and tries to be as far as possible "convention over configuration".
The framework provide all basic features for develop Restful APIs, Websites and web applications.

# Requirements
Actually the frameworks is on heavy development. it has been tested with Apache (need .htaccess mod_rewrite) and built-in PHP 7 server (URL rewrite by root).


## Features and Roadmap
\* marked are under development or analysis

### Main features
  * Small footprint
  * Polymorphic
  * Totally object oriented
  * Easy to use & intuitive
  * ~~0 dependencies required~~
  * PHP 7+
  * easy extensibility (ServiceProviders etc.)*
  * Almost no configuration (No yaml, xml, ini.. Just PHP)
  * Great Performance, ease of use and speed of development!
  * Dependency Injection done via Container (PHP-DI)
  * PSR-4, PSR-7*, PSR-11 compatible
  
* PSR-7 is actually extended, since pHackp ServerRequestInterface supports path params for restful routes.

### Routing
  * Routing is made by php code, no server configuration rules generation or requirement (just mod_require to index.php)
  * array mapped routes
  * actions are mapped by Class and Method name for given url
  * exclusion of non-mapped routes (return configurable error routes)
  * 100% pretty urls (e.g. ...blog/title/1)
  * parameter bound with simple tag {tag} (e.g. tag/{tag}/)
  * REST method mapping (GET,POST,PUT,DELETE,HEAD,OPTIONS,PATCH)
  * rich set of RESTful features (e.g. auto json decoding/encoding)
  * GET parameters injection maps to given tag name {name} ($serverRequest->getQueryParams()['name'])

### Persistence
   * object-relational (ActiveRecords) mapping engine built on top of PDO. (HackORM)
   * API for intuitive access
   * Auto model mapping (convention-based)
   * OneToMany, ManyToOne, ManyToMany relationships between objects
   * Parent implementation of behaviours (e.g. $object->save() .)
   * Dependency injection (e.g self::oneToMany($this, 'Parent::class') returns $Parent instances.)
   * Ability to use Database class as standalone to preserve "is a" OOP rule. (but Model API is more productive!)
   * Models can always access PDO instance when needed ($this->getPDO())
   
### Model View Controller
  * Application lifecycle will route the current request to the specified controller method, also when errors or 404.
  * Controllers takes advantages of DI, so your are free to Inject what you need (Views, Sessions, Models, Requests)
  * built-in template system support template inheritance. (plain php!)
  * it's all about conventions!

### Presentation layer
  * Built-in engine for views
   * plain PHP syntax
   * template inheritance
   * Dynamic Hook support
   * Configurable Hooks with scope isolation (or passed-by)
   
### Services and Providers
   * Dynamically register providers instances (non-singleton, single runtime instance beans)
   * Reflection based invocation of interfaces methods, allowing to delegate implementation class @ runtime (eg. ErrorHandlers)
   * Easy and effective API to extends and implements brand new providers or delegates
   * Both Eager and Lazy provider instantiation
   * Configurable, extensible and fully integrated into framework container
   * Ready to use ServiceProviders
   * ErrorHandler (Provide PHP error and exception handling overrides)
   
### PSR-4
  * use composer default autoload psr-4
  * auto mapping for project classes
  
### PSR-7
   * ServerRequest interface is created and set into DI container
   * Controller method use DI to get the current request via type injection (autowiring)
   
### PSR-11 (draft)
  * We use PHP-DI as reference implementation
  * Framework dependencies factories are managed by pHackp runtime
  
### RESTful
  * Easy, simple straightforward REST API creation
  * routes GET,POST,PUT,DELETE,OPTIONS,PATCH etc. for CRUD RESTFUL standard API's
  * automatic serialize/un-serialize json-to-array (PSR-7 Middleware)
  * Ready to use CORS filter*
