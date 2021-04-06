[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fdarksworm%2Fopenchat_kata_outside_in%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/darksworm/openchat_kata_outside_in/master) [![Coverage Status](https://coveralls.io/repos/github/darksworm/openchat_kata_outside_in/badge.svg?branch=master)](https://coveralls.io/github/darksworm/openchat_kata_outside_in?branch=master)

Openchat API implemented using Outside-In/London style TDD with PHP8 and Laravel similarly as in the [London vs. Chicago video series]( https://cleancoders.com/series/comparativeDesign).

---

# Learnings / Decisions

Testing the framework is painful, instead of unit-testing controllers and repositories I instead opted to leave them covered only by feature tests.

Striking the SRP balance is really difficult. I was often left with Services and Controllers with only one method each - that feels wrong but seems to be the right choice most of the time.

Choosing which classes to create interfaces for is not straight-forward - I ask myself, Is this likely to need to be swapped for something else? 

Although I have pushed all of the framework crap away in its own directory, it has bled into the BL due to it's ease of use - having collections is just too nice. I'm starting to wonder whether BL should depend only on separate libraries instead of the framework or depend on nothing at all.

Feature/Integration/Acceptance tests (whatever you call them) need to be approached as any other piece of code - they have to be built using the same priciples otherwise they end up as spaghetti code. I opted to [extract common API calls as trait classes](https://github.com/darksworm/openchat_kata_outside_in/tree/master/laravel/tests/Feature/API), [do the same for data providers](https://github.com/darksworm/openchat_kata_outside_in/tree/master/laravel/tests/Feature/Providers), [asserts](https://github.com/darksworm/openchat_kata_outside_in/blob/master/laravel/tests/Feature/Shared/AssertsDateTimes.php) and [common test cases](https://github.com/darksworm/openchat_kata_outside_in/blob/master/laravel/tests/Feature/Shared/TestsEndpointExistence.php)
That is probably also the case for unit tests, but I am yet to refactor them - seems like some common mocks can be extracted.

It is easy to write tests with TDD which pass not due to the expected logic executing but the next logic having the same effect - I was throwing 3 instances of the same exception in a unit and the tests continued to pass when I removed the first or first two throws. I found this via mutation tests.

It is not possible or necessary to achieve a 100% mutation score. How to spot harmful mutations stays an open question for me.
