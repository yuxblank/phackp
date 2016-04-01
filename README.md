# phackp
PHP Modern Micro Framework

# Introduction
phackp aim to provide a fast and easy way to build modern and resposive websites and apps.
It provides an Model View Controller structure for organizing your project and tries to be as far as possible "convention over configuration".


## Features
* Routing
  * json mapped routes
  * exclusion of non-mapped routes (return a routable 404 page mapping)
  * 100% pretty urls (e.g. ...blog/title/1)
  * parameter bound with simple tag {tag} (e.g. tag/{tag}/)
  * REST method mapping (GET,POST,DELETE,PUT)
  * auto parameters injection to target method signature
  * rich set of RESTful features (e.g. auto json decoding/encoding)
* Persistence
  * object-relational mapping engine built on top of PDO.
  * auto model mapping (convention-based)
