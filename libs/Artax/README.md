### WHAT IS IT?

**Artax** is a baseline scaffold for creating event-driven PHP 5.3+ applications.

*NOTE: Please be aware that interfaces and implementation are subject to
change until an official version 1.0 is reached.*

### FEATURES

* Mediated [event management][wiki-events] for pluggable applications
* Built-in [dependency injection][wiki-dic]
* Lazy-loading of class-based event listeners
* Unified application [error, exception and shutdown][wiki-errors] event handling

### PROJECT GOALS

* Implement an event-driven application engine without inhibiting linear
cause/effect design;
* Integrate simple, built-in dependency injection;
* Lazy-load anything that can be put off without performance penalties;
* Eschew the use of `static` entirely in favor of maximum testability and 
full API transparency;
* Favor OOP principles for complex application development while supporting
organized lambda usage in evented systems;
* Build all components using [SOLID][solid], readable, documented and 100% 
unit-tested code.

### WHAT'S WITH THE NAME?

Children of the 1980s are likely familiar with [The NeverEnding Story][neverending] 
and may remember the scene where Atreyu's faithful steed, Artax, died in the Swamp
of Sadness. The name is an homage to one of the greatest childrens movies of all
time.

### EPILOGUE

If you don't have much experience developing PHP applications, this may not be
the right tool for you. There is no built-in MVC structure, though it should be
trivial to implement an evented MVC application on top of the Artax-Core
package. There are no "helper" libraries for generating emoticons or HTML
forms: just a SOLID, readable, documented, unit-tested scaffold for writing
event-driven and pluggable PHP applications.

[solid]: http://en.wikipedia.org/wiki/SOLID_(object-oriented_design) "S.O.L.I.D."
[neverending]: http://www.imdb.com/title/tt0088323/ "The NeverEnding Story"
[wiki-events]: https://github.com/rdlowrey/Artax/wiki/Event-Management
[wiki-dic]: https://github.com/rdlowrey/Artax/wiki/Dependency-Injection
[wiki-errors]: https://github.com/rdlowrey/Artax/wiki/Error-Management

