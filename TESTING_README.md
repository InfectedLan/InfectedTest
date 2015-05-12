Test writing documentation
==========================

Hey! If you are reading this, you have propabily been tasked in writing tests for the huge Infected.no website. It is pretty easy once you get a hang of it, but it does get tiring after a while. Please read through this, and look at the UserTestSuite test for examples.

Assertion
=========
_Assertion - to state with assurance, confidence, or force; state strongly or positively; affirm; aver:_
 - dictionary.reference.com

In a testbed, we assert the testbed that the value of a variable, or the return of a function, is something. If this assertion is false, that assertion has failed.
When you write tests, all you are doing is telling the testbed that at a variable should be something. This way, we can check if a function behaves poorly.

Let's take something simple, like addition, as an example. If you were to test it, you might do something like this:

```$this->assert_equals(1+1, 2);```

This tells the testbed that 1+1 is supposed to be 2. If it isn't, the system logs it for further analysis by whoever runs the tests.

Assertion functions
===================
These are the current functions we support:

 * ```assert($value)``` - Asserts that $value is true
 * ```assert_equals($value, $expected)``` - Asserts that $value is equals to $expected. Please note that this is also type specific.
 * ```assert_not_equals($value, $expected)``` - Asserts that $value is not equals to $expected. Also type specific.
 * ```assert_compare($value1, $value2)``` - Asserts that $value1 is equals to $value2. Identic to ```assert_equals```, but has different fail message for easier debugging
 * ```report($result, $comment)``` - Custom report. $result should be a constant from ```TestResult```. $comment is whatever you want to say. It should tell whoever views the testlog why you chose to do a custom report instead of using an inbuildt method. Should mostly only be used for cases where the result is ```TestResult::TEST_NOT_RAN``` or ```TestResult::TEST_PASSED_WITH_WARNING```. Use common sense.

Starting the tests
==================

Currently, to start the tests, run ```InfectedTestbed.php``` from a web browser. There is a command line tester, but it is not in use as the web report is easier to read. A SQL reporter is in planned, but not prioritized right now.

General rules and best practise
===============================

 * There should be one feature tested per testbed. This does not mean you can't call outside methods if you have to, but keep the focus to one per testbed
 * You should divde the testbed into multiple functions for testing different aspects of the feature
 * All features of the feature should generally be tested, and preferabily in multiple ways to make sure there are no errors not caught by one test. Use common sense
 * You don't need to explicitly test a subfeature if it is tested as a part of another subfeature test, as long as they are in the same testbed.
 * Please note that the testbed logs the function that called the assertion, and the line number in the testsuite.

Creating your own tests
=======================

 * Create a new class in the ```tests``` folder using same filename as classname(Same upper and lower letters)
 * Add the class to the array of test suites in the constructor for ```InfectedTestbed```. Classes are automatically loaded from a similarly named file.
 * Let the class extend the class ```TestSuite```
 * Override the function ```test()```
 * Write functions called from ```test()``` that does the testing