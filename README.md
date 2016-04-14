# phackp
PHP Micro Framework, born in PHP7 Age!

# disclaimer

pHackp is actually in alpha stage.
the framework might change in ways that make alpha/beta releases un-compatible with the first stable version in the future.
The framework is actually compatible with PHP7+.

# Introduction
pHackp aim to provide a fast and easy way to build modern and resposive websites and apps.
It provides an Model View Controller structure for organizing your project and tries to be as far as possible "convention over configuration".

# Requirements
Actually the frameworks is on heavy development. it has been tested with Apache and built-in PHP 7 server only. I planned to provide support for both Apahce, Ngnix and IIS.


## Features and Roadmap
\* marked are under development or analysis

### Main features
  * Small footprint
  * Totally object oriented
  * Easy to use
  * 0 dependecies required
  * PHP 7
  * easy extendibility (API's, Modules etc.)*
  * Almost no configuration (No yaml, xml, ini.. Just PHP)*

### Routing
  * array mapped routes
  * actions are mapped as Controller@method
  * exclusion of non-mapped routes (return configurable error routes)
  * 100% pretty urls (e.g. ...blog/title/1)
  * parameter bound with simple tag {tag} (e.g. tag/{tag}/)
  * REST method mapping (GET,POST,DELETE,PUT)**
  * auto parameters injection to target method signature
  * rich set of RESTful features (e.g. auto json decoding/encoding)*

### Persistence
   * object-relational mapping engine built on top of PDO.
   * API for intuitive access
   * auto model mapping (convention-based)
   * OneToMany, ManyToOne, ManyToMany relationships between objects
   * Self reflecting methods (Abstract) (e.g. $object->save() .)
   * dependency injection (e.g self::oneToMany($this, 'Parent') returns $Parent.)*
   * Singleton Database instance provides one connection per execution
   * ability to use Database standalone to preserve "is a" OOP rule. (but Model API is more productive!)*
   
### Model View Controller
  * Controller API provides all useful stuff (sessions, flash scopes, security etc...)*
  * simple API for models
  * view variables rendering from controllers (e.g $View->renderArgs("name", value) accessible from view as $name.)
  * view render specific template (e.g. $View->render("app/blogpage") .)
  * built-in template system support template inheritance.*
  * it's all about conventions!

### Presentation layer
  * Built-in engine for views
   * plain PHP syntax
   * template inheritance
 
### PSR-4
  * use composer default autoload psr-4
  * auto mapping for project classes
  
### RESTful
  * routes GET,POST,PUT,DELETE for CRUD API's
  * automatic serialize/un-serialize json-to-array
  * content-type filtering (via routes options)
