# StopInjection

> This library is still under heavy development and has not had any version released yet.

StopInjection is a project intended to:

- Help educate developers about security vulnerabilities that may be present in their codebase
- Help guide developers to taking the appropriate steps to fix found vulnerabilities
- Provide static analysis to determine source code that may be susceptible to specific vulnerabilities

Primarily StopInjection is focused on vulnerabilities that are the result of user input being injected maliciously.

## Installation

### Usage of the library

> Please note that as StopInjection has had no release this method of installation is not yet supported.

It is recommended that you use [Composer](https://getcomposer.org) to install this library.

Directly from command line:

```shell
composer require cspray/stop-injection 0.1.*
```

Through `composer.json`:

```json
{
    "require": {
        "cspray/stop-injection": "0.1.*"
    }
}
```

### To contribute

I highly recommend that you fork this repository on GitHub and then read through the [CONTRIBUTING](https://github.com/cspray/StopInjection/blob/master/CONTRIBUTING.md) doc.

## Disclaimer

The primary developer and contributors to this project and codebase **make no guarantees that all, or any, security vulnerabilities will be detected by the static analyzers made available**. The use of the static analyzers or the command line tool...

 - will **NOT** magically fix your code and make it secure from malicious attacks
 - will **NOT** ensure that usages reported as secure are not susceptible to, some other non-targeted attack; the attack targeted for analysis; any uknown attack made possible with future advancements in technology or changes to the PHP language itself.
 - will **ONLY** target specific attacks and suggest *possible* solutions
 - will **NOT** guarantee that suggested solutions will prevent malicious attacks targeted for analysis and/or non-targeted attacks.
 - will **NOT** guarantee that reported usages are the only security vulnerabilities in your codebase or with the specific source code in question.

You assume all risk and liability from the use of this source code and implementing any changes or refactorings suggested by this project.

While we strive to have a thoroughly tested, well reviewed codebase that is capable of detecting these vulnerabilities please review the codebase before using the library or the command line tool, particularly in a production environment.

## StopInjection\Extract

This module analyzes a codebase for the use of the `extract` function and pinpoints places in the codebase where use of the function could result in malicious user input being injected into your application. Please check out the `/doc/extract/` directory for more on how misuse of the extract function can negatively impact your applications.

### Usage

StopInjection is designed in such a way that you should be able to use it from the CLI or through some build process or other library integration.

#### Through CLI

*This has not yet been implemented*

#### As a library in your own applications or build process

You can also use this library outside the CLI tool and can integrate with your own static analysis app or build processes. We assume in the example usages below that you have autoloading appropriate setup.

```php
<?php

use \StopInjection\Analyzer\Printer\TextPrinter;
use \StopInjection\Extract\Report as ExtractReport;
use \StopInjection\Extract\Analyzer as ExtractAnalyzer;

$BuildReport = new ExtractReport('/path/file.php', \time());
$Analyzer = new ExtractAnalyzer(new \PHPParser_Parser(), new \PHPParser_NodeTraverser());

$code = getCodeFromApp(); // this is provided by you

$Analyzer->analyze($code, $BuildReport);

$Printer = new TextPrinter();
echo $Printer->printReport($BuildReport);
```

Assuming that the `/path/file.php` has three usages of `extract`, one secure and two insecure then you should see output similar to the following:

```shell
Extract Analysis Report
    A report to analyze vulnerable usages of extract function

File analyzed: /path/file.php
Report run date: 2013-01-01 01:00:00

Total usages found:         3
    Secures usages:         1
    Insecure usages:        2
    Susceptible usages:     0


Secure usages
================================================================================

#1 on line 10
- A detail explaining why we believe this is secure usage


Insecure usages
================================================================================

#1 on line 35
- A detail explaining why we think this might be susceptible and warrants further inspection
- A detail explaining why we think the usage is insecure after more inspection

#2 on line 50
- A detail explaining why we think this might be susceptible and warrants further inspection
- A detail explaining why we think the usage is insecure after more inspection
- A detail explaining why we think extracting a superglobal is a codesmell with a suggestion to read /docs/extract


Susceptible usages
================================================================================

No usage of this type found


End of report
```

## Errata

We strive for this codebase to be thoroughly tested and to be as accurate as humanly possible. However, humans do make mistakes and last I checked I fall into that category. If you find something wrong with the analysis algorithm, think that an analysis detail is wrong or have a better suggestion for fixing a vulnerability **please** [submit an issue](https://github.com/cspray/StopInjection/issues/new) to this repository. I will strive to ensure that any errors found or reasonable suggestions will be quickly responded to and implemented.

## Contributors

Charles Sprayberry
- title: Lead Developer
- blog: [http://cspray.github.io/](http://cspray.github.io/)
- contact: sprayfire.framework@gmail.com
- twitter: [@charlesspray](https://twitter.com/charlesspray)

