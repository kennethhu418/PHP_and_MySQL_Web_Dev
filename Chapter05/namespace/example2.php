<?php
namespace MyNameSpace\SubSpace;

class MyClass 
{
    function __construct()
    {
        echo __NAMESPACE__."::MyClass Construct <br />";
    }
}

function MyFunc()
{
    echo __NAMESPACE__."::MyFunc is called <br />";
}

const constname = "MyNameSpace\SubSpace::variable is printed <br />";

require('example1.php');

$a = 'MyClass';
$obj = new $a;
$b = 'MyFunc';
$b(); 
echo constant('constname')."<br /><br />"; 

// When dynamically accessing elements in PHP, must use fully qualified space name
// http://jp2.php.net/manual/en/language.namespaces.dynamic.php
$a = "\\".__NAMESPACE__.'\MyClass'; 
$obj = new $a; 
$a = __NAMESPACE__.'\MyClass';
$obj = new $a; 
$b = __NAMESPACE__.'\MyFunc';
$b(); 
$b = __NAMESPACE__.'\MyFunc';
$b(); 

echo constant('MyNameSpace\SubSpace\constname')."<br /><br />";
?>
