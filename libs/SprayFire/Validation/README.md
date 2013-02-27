## SprayFire\Validation

**Dependencies:**

- SprayFire\Validation\Check
- SprayFire\Validation\Result
- SprayFire\Object

The Validation module allows developers to ensure that a piece of data matches a specific format, type or convention for what is considered valid for that particular piece of data. It does not, in any way, manipulate or change the data, it simply let's you know if it is valid or not. The Validation module is one of the more complex modules, although it is still easy to use, and has a few different parts to it. Check out the rest of this Module's docs then head over to take a look at the Validation\Check Module and the Validation\Result Module.

> Right now this module is in a very volatile state, it is highly likely to change in a way that breaks v0.1.0a functionality in future versions.

### SprayFire\Validation\Validator

The Validator doesn't hold algorithms to check the validity for a set of data. Rather it is the process in which a set of data is checked against a set of Rules for that set of data. In v0.1.0a the Validator only works on a set of data and the rules for that data set must be passed when validation occurs. Some changes are planned for this module in v0.2.0a that will add to or change this behavior.

### SprayFire\Validation\Rules

The Rules hold a series Validation\Check\Check objects that should be ran against a field in the data set. You can think of Rules as a "container of chains", checks are ran against a field in the order they are added to the chain and you should be able to break out of a field's chain anytime you want. Each field is its own chain, this is important to remember.

> Currently there is a design flaw in `Rules::getChecks($field)` in that the Validator instance must be aware of the arbitrary internal structure returned from this method. This is necessary to determine whether or not the check at that point in the chain should break the rest of the chain for that field. In addition it is highly likely that we will need to add more arbitrary structure to the element causing even more coupling between the Rules and the Validator. A better design could include returning a specific interface but that may be increasing object creation for a module already heavy in object creation.