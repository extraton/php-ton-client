# How to contribute

If you have improvements to PHP Ton Client, send us your pull requests!   
PRs to our libraries are always welcome and can be a quick way to get your fix 
or improvement slated for the next release.

In general, we follow the ["fork-and-pull" Git workflow](https://github.com/susam/gitpr):

1. Fork the repository to your own Github account
2. Clone the project to your machine
3. Create a branch locally with a succinct but descriptive name
4. Make changes
5. Write tests
6. Run tests, phpstan, check codestyle.
7. Commit changes to the branch
8. Push changes to your fork
9. Open a PR in our repository and follow the PR template so that we can efficiently review the changes.

ExtraTON team members will be assigned to review your pull requests.  

Also you can report a problem via [issue section](https://github.com/extraton/php-ton-client/issues)
or in [telegram chat](https://t.me/extraton).

### Coding style
Changes to PHP Ton Client code should conform to 
[PSR2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

Use php-cs-fixer to check your changes:
                  
    make codestyle

### Running tests

    make test

### Running phpstan checks

    make analyze
