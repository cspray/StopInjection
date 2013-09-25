<?php

require_once 'vendor/autoload.php';

use \StopInjection\Command\CommandProvider as SICommandProvider;
use \SpraySole\BasicApplication;
use \SpraySole\Input\ArgvInput;
use \SpraySole\Output\StreamOutput;
use \SpraySole\Provider\DefaultCommandProvider;

$App = new BasicApplication();
$App->registerProvider(new DefaultCommandProvider());
$App->registerProvider(new SICommandProvider());


$Report = new \StopInjection\Extract\Report('ido567/telemz/telemz/orders/inc/install_var.php', \time());

// Displays all array indices and object properties
ini_set('xdebug.var_display_max_children', -1);

// Displays all string data dumped
ini_set('xdebug.var_display_max_data', -1);

// Controls nested level displayed, maximum is 1023
ini_set('xdebug.var_display_max_depth', -1);

$code = <<<'PHP'
<?php

extract(array('a' => 'aval'));
var_dump($a);
PHP;

$Analyzer = new \StopInjection\Extract\Analyzer(new \PHPParser_Parser(new \PHPParser_Lexer()), new \PHPParser_NodeTraverser());
$Analyzer->analyze($code, $Report);

$Printer = new \StopInjection\Analyzer\Printer\TextPrinter();

echo '<pre>', $Printer->printReport($Report), '</pre>';
exit;

$Input = new ArgvInput($argv);
$StdOut = new StreamOutput(\STDIN);
$StdErr = new StreamOutput(\STDERR);

$App->run($Input, $StdOut, $StdErr);
