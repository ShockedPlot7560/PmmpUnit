## PmUnitTest - Unit Testing Framework for PocketMine

This plugin has been created with the sole aim of making unit, integration and functional tests possible during the pocketmine runtime.  

It can be used to correctly test the expected result of a command executed by a fake player, interaction with events and multi-threading support.

Tests are run asynchronously using promises to enable support with different systems in place. The libraries used for the promises are : [ReactPHP/promise](https://github.com/reactphp/promise)

> [!WARNING]
> This plugin should not be used in production, as it will shutdown the server as soon as testing is complete.

## Utilisation

Tests are retrieved from the folder: `plugin_data/PmUnitTest/tests/` and are executed when the server start.

All test classes must inherit from `ShockedPlot7560\UnitTest\frameworkTestCase`.  
All test methods must be prefixed with `test` and must not have any parameters, it must return a `PromiseInterface<null>`.  
If the code is executed synchronously and does not have to wait for a result, use `resolve(null)`.
Use `Deferred` instead for asynchronous code and `$deferred->resolve(null)` to resolve the promise.

Each test class can execute code when the server is `onLoad`, `onEnable` or `onDisable`. Simply use the corresponding classes.

`setUp` and `tearDown` are called when each class test is run, respectively before and after the test, whatever the result.

### TestPlayer utilisation

To retrieve one player during a test, call ``getPlayer()``.   
To test player commands or behavior, create your promise/listener before executing the action. Return the promise in the test method.