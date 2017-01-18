# phackp
PHP Micro Framework, born in PHP7 Age!

# disclaimer

pHackp is actually in alpha stage.
the framework might change in ways that make alpha/beta releases un-compatible with the first stable version in the future.
The framework is actually compatible with PHP7+.

# Introduction
pHackp aim to provide a fast and easy way to build modern and resposive websites and apps.
It provides an Model View Controller structure for organizing your project and tries to be as far as possible "convention over configuration".
The framework provide all basic features for develop Restful APIs, Websites and web applications. it has no dependencies and it's not a fully fledged framework as many others in PHP panorama: you get the basis to work for any kind of project (MVC, Routing, Template, Pretty URL, Database Access, Sessions, REST, Services and Providers *cache to come*) and when something is missing you just: *composer require X*. That's it!

There are plenty of Frameworks wrapping the world of PHP packages, why just don't use them?

# Requirements
Actually the frameworks is on heavy development. it has been tested with Apache (need .htaccess mod_rewrite) and built-in PHP 7 server (URL rewrite by root).


## Features and Roadmap
\* marked are under development or analysis

### Main features
  * Small footprint
  * Totally object oriented
  * Easy to use
  * 0 dependecies required
  * PHP 7
  * easy extendibility (API's, Modules etc.)*
  * Almost no configuration (No yaml, xml, ini.. Just PHP)
  * Great Performance, ease of use and speed of development!

### Routing
  * array mapped routes
  * actions are mapped by Class and Method name for given url
  * exclusion of non-mapped routes (return configurable error routes)
  * 100% pretty urls (e.g. ...blog/title/1)
  * parameter bound with simple tag {tag} (e.g. tag/{tag}/)
  * REST method mapping (GET,POST,PUT,DELETE,HEAD,OPTIONS,PATCH)
  * auto parameters injection to target method signature
  * rich set of RESTful features (e.g. auto json decoding/encoding)
  * GET parameters injection maps to given tag name {name} ($param['name'])

### Persistence
   * object-relational mapping engine built on top of PDO. (HackORM)
   * API for intuitive access
   * auto model mapping (convention-based)
   * OneToMany, ManyToOne, ManyToMany relationships between objects
   * Self reflecting methods (Abstract) (e.g. $object->save() .)
   * dependency injection (e.g self::oneToMany($this, 'Parent::class') returns $Parent instances.)
   * ability to use Database class as standalone to preserve "is a" OOP rule. (but Model API is more productive!)
   * Models can always access PDO instance when needed ($this->getPDO())
   
### Model View Controller
  * Controller API provides all useful stuff (sessions, flash scopes, security etc...)*
  * controllers/models can be put in any folder/s, the classloader will do the rest
  * simple API for models
  * view variables rendering from controllers (e.g $View->renderArgs("name", value) accessible from view as $name.)
  * view render specific template (e.g. $View->render("app/blogpage") .)
  * Configurable Hooks with scope isolation (or passed-by)
  * built-in template system support template inheritance. (plain php!)
  * it's all about conventions!

### Presentation layer
  * Built-in engine for views
   * plain PHP syntax
   * template inheritance
   * Dynamic Hook support
   * Scope isolation (hooks)
   
### Services and Providers
   * Dynamically register providers instances (non-singleton, single runtime instance beans)
   * Reflection based invocation of interfaces methods, allowing to delegate implementation class @ runtime (eg. ErrorHandlers)
   * Easy and effective API to extends and implements brand new providers
 
### PSR-4
  * use composer default autoload psr-4
  * auto mapping for project classes
  
### RESTful
  * Easy, simple straightforward REST API creation
  * routes GET,POST,PUT,DELETE,OPTIONS,PATCH etc. for CRUD RESTFUL standard API's
  * automatic serialize/un-serialize json-to-array
  * content-type filtering (via routes options)*
