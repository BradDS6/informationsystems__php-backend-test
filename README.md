# IS backend exercise

## Summary

The aim is to calculate some statistics on two models stored in the database, a `Job` and a `DeliveredRevenue` model. This is a simplification of a revenue handling system for digital media (advertising).

For `DeliveredRevenue`s you are aiming to calculate the total revenue, total impressions, eCPM and average eCPM for a given Order. 

For `Job`s you need to calculate all of the above for all revenue in the job, and also determine if it has met its revenue target and over how many months it has run. 

An interface (`StatisticsService`) is provided for you to implement. You will need to bind your implementation to the Laravel IOC container in order for the tests to run. 

See [this page](https://www.marketingterms.com/dictionary/ecpm/) for information on calculating the eCPM (effective cost per mile) figure.

Test are provided in `tests/Feature`, see below for how to run them.

## Setup

You will need docker / docker-compose installed on your machine, i.e Docker Desktop.

A Makefile is provided. Run `make shell` to get a bash shell to run composer etc in. 

Run `make test` to run phpunit tests.

A php_cs config file is provided, run `vendon/bin/php-cs-fixer fix` to fix code style. 

## What we're looking for.
 
1. Use Eloquent to retrieve seeded Models from the database where appropriate. 
1. Write well structured code to pass the failing test suite.
1. Add unit tests to cover your new code.
1. Clear and concise commit messages.

You may modify this repository how you see fit.
 
## Brad's commit
Added code to pull records from database tables based on testing criteria.
Calculated data based on criteria/instructions from initial README.md
Returned data to be displayed during PHP Unit testing.
