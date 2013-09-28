# Superglobal Injection

This article talks about what superglobal injection is, how code can be susceptible to attacks, and how it is relatively easy to secure `extract` vulnerabilities. For this article it is assumed that you understand the basic mechanisms of the [`extract`](http://www.php.net/extract) function in PHP.

## What is superglobal injection?

 A malicious attack targeting improper use of the `extract` function that allows users to write content into PHP [superglobals](), other globals in general and variables in the scope of the extract function. Through this attack the attacker may gain access to unauthorized portions of your web application, expose confidential data or inject remotely executed code into your application.

## How might my codebase be susceptible to this attack?

As mentioned before this attack targets improper use of the `extract` function and its root cause, outside of using `extract` improperly, is typically unsafe handling of user input. The `extract` function will take the keys in the target associative array and import them into the current symbol table. If that doesn't make sense to you think of it as creating variables in the current scope with the key in the array being the variable name and the associated array element is the value. The primary security vulnerability comes when one of those keys is, for example, '_SESSION' or one of the other superglobals. If you wind up extracting user input and they have set a key to one of these values you could be in for a bad experience.

## How can I secure against this kind of attack?



