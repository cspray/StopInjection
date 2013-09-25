# StopInjection

> This library is still under heavy development and has not had any version released yet.

StopInjection is a project intended to:

- Help educate developers about security vulnerabilities that may be present in their codebase
- Help guide developers to taking the appropriate steps to fix found vulnerabilities
- Provide static analysis to determine source code that may be susceptible to specific vulnerabilities

Primarily StopInjection is focused on vulnerabilities that are the result of user input being injected maliciously.

## Installation

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

## Disclaimer

The primary developer and contributors to this project and codebase **make no guarantees that all, or any, security vulnerabilities will be detected by the static analyzers made available**. The use of the static analyzers or the command line tool...

 - will **NOT** magically fix your code and make it secure from malicious attacks
 - will **NOT** ensure that usages reported as secure are not susceptible to, some other non-targeted attack; the attack targeted for analysis; any uknown attack made possible with future advancements in technology or changes to the PHP language itself.
 - will **ONLY** target specific attacks and suggest *possible* solutions
 - will **NOT** guarantee that suggested solutions will prevent malicious attacks targeted for analysis and/or non-targeted attacks.
 - will **NOT** guarantee that reported usages are the only security vulnerabilities in your codebase or with the specific source code in question.

You assume all risk and liability from the use of this source code and implementing any changes or refactorings suggested by this project.

While we strive to have a thoroughly tested, well reviewed codebase that is capable of detecting these vulnerabilities please review the codebase before using the library or the command line tool, particularly in a command line environment.

## StopInjection\Extract

This module analyzes a codebase for the use of the `extract` function and pinpoints places in the codebase where use of the function could result in malicious user input being injected into your application. Please check out the `/doc/extract/` directory for more on how misuse of the extract function can negatively impact your applications.

### Usage

StopInjection is designed in such a way that you should be able to use it from the CLI or through some build process or other library integration.

#### Through CLI



#### As a library in your own applications or build process


### Example Output




