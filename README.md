[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fdarksworm%2Fopenchat_kata_outside_in%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/darksworm/openchat_kata_outside_in/master) [![Coverage Status](https://coveralls.io/repos/github/darksworm/openchat_kata_outside_in/badge.svg?branch=master)](https://coveralls.io/github/darksworm/openchat_kata_outside_in?branch=master)

Openchat API implemented using Outside-In/London style TDD with PHP8 and Laravel similarly as in the
[London vs. Chicago video series](https://cleancoders.com/series/comparativeDesign).

N.B. The react front-end does not work unmodified with this, you have to apply the `react_post_as_json.patch` patch to
it because laravel does not understand `application/x-www-form-urlencoded` apparently.

---

## Learnings

### TL;DR

1. Don't test the framework; test the behaviour of the framework instead.
1. Approach tests as any other piece of code; otherwise they end up as spaghetti.
2. In most cases, prefer DAMP over for unit tests.
3. Use mutation testing; otherwise your tests might be falsely covering your code.
4. Don't use static mock data for feature tests; otherwise deadlocks will pop up when running tests in parallel.
5. Not letting the framework creep into your BL is hard, but can be done and is probably wise.

### Testing the framework

Testing the framework is painful, instead of unit-testing controllers and repositories I instead opted to leave them
covered only by feature tests. This allows me to quickly change the controllers, validators and other framework-specific
crap without having to worry about how to mock it in tests - instead the tests verify the behavior of the framework.

### Acceptance tests

These tests need to be approached as any other piece of code - they have to be built using the same principles otherwise
they end up as spaghetti. I opted to extract commonly used code to Traits:
[API calls](/laravel/tests/Feature/API),
[data providers](laravel/tests/Feature/Providers),
[asserts](/laravel/tests/Feature/Shared/AssertsDateTimes.php) and
[common test cases](/laravel/tests/Feature/Shared/TestsEndpointExistence.php).

Using these traits results in the test classes which begin like this:

```php
class GetMyWallTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers, FollowsUsers, CreatesPosts;
    use InvalidUuidProvider;
    ...
}
```

The important part is the middle line - which tells us which features or use-cases this acceptance test uses.

### Unit Tests
A lot of method mocks I've written are repeated multiple times. A method I've used in 4 separate places in the production code ended up with 9 separate instances of it being mocked. My initial instinct is to refactor and make it DRY, but there is no reasonable way of doing so and it would hurt readability of the tests. I've stumbled upon some [DAMP vs DRY conversations](https://stackoverflow.com/questions/6453235/what-does-damp-not-dry-mean-when-talking-about-unit-tests) and come to the conculsion that it's better to leave the mocks as is - DAMP.

At first glance, the unit tests seem very fragile because they test the implementation and because you have to mock some methods over and over, but it should not be a problem if the underlying design is good - for example, I feel confident that mocking `UserService::validateUsersExists` will not result in a lot of pain down the line, because that method has **only one reason to change** and its sideffects are **unlikely to change**.


### TDD

It is easy to write tests with TDD which pass not due to the expected logic executing, but the next logic having the
same effect - I was throwing 3 instances of the same exception in a unit, and the tests continued to pass when I removed
the first or first two throws. I found this via mutation tests.

It is not possible or necessary to achieve a 100% mutation score. How to spot harmful mutations while ignoring harmless
mutations stays an open question for me - maybe some tagging system? Mutation tests really are tests for your tests and
I feel that they are necessary to verify that the tests you're writing don't falsely cover your code.

### SOLID

Striking the SRP balance is not straight-forward. I was often left with Services and Controllers with only one method
each - that feels wrong but seems to be the right choice most of the time.

Choosing which classes to create interfaces for is not straight-forward - I ask myself, Is this likely to need to be
swapped for something else? I chose to create interfaces for the repositories not only because in my experience swapping
DBMS is not an uncommon occurrence in the life of a project, but also because I want to make it harder to add shitty
queries - the less shitty SQL there is, the better.

### PHPUnit

I had to battle its config to get feature tests to run after unit tests. It was also very slow to execute tests, so I
installed `paratest` which runs them in parallel, which works very nicely unless your acceptance tests create deadlocks
due to manipulating rows with the same unique identifiers... (don't do that!)

### Laravel

Although I have pushed all the framework crap away in its own directory, it has bled into the BL due to its ease of use - 
having collections is just too nice. I'm starting to wonder whether BL should depend only on separate libraries
instead of the framework or depend on nothing at all.

Organizing database migrations in the same project as the BL resides just seems like a bad idea to me. This means your
web application must have not only read/write access to change data but also write access to add/remove tables. For
better security the migrations should be executed by a different database user - something that laravel does not suggest
you should do.

### Swoole vs NGINX

with 6 users in the database and using `wrk` to load-test the `GET /users` endpoint thusly:
```
wrk -t8 -c100 -d60s --timeout 10s http://localhost/users
```

#### NGINX
```
Running 1m test @ http://localhost/users
  8 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     2.67s   397.49ms   3.04s    88.28%
    Req/Sec    10.44      8.19    50.00     58.28%
  2107 requests in 1.00m, 1.61MB read
Requests/sec:     35.06
Transfer/sec:     27.49KB
```

#### Swoole
```
Running 1m test @ http://localhost/users
  8 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   121.34ms   46.64ms 425.52ms   68.40%
    Req/Sec    96.80     33.98   292.00     74.36%
  46351 requests in 1.00m, 33.82MB read
Requests/sec:    771.30
Transfer/sec:    576.22KB
```
