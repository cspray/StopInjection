# Analysis Security Levels

A large part of the StopInjection modules is the ability to determine whether or not a targeted usage is secure or not. To aid in comprehension for the reports generated by the modules we have a standard when analyzing source code. This document details the meanings of the terms that you'll encounter when working with the codebase or consuming reports generated by the codebase. There are three security levels: Secure, Insecure and Susceptible. While these are pretty self-explanatory check out the details below for more information.

## Secure

We have determined this usage is secure against the vast majority of vulnerabilities. Typically these usages are determined secure because (1) the usage of the \EXTR_SKIP second argument or (2) the correct usage of the \EXTR_PREFIX in the second argument and the actual prefix value in the third argument.

## Insecure

We have determined this usage is insecure and is very highly likely to be a viable entry point in a malicious attack. Typically we determine a usage is insecure because (1) a variable is being extracted and (2) invalid second parameter usage.

## Susceptible
