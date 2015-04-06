<?php
class MyClass 
{
    function __construct()
    {
        echo "global MyClass Construct"."<br />";
    }
}

function MyFunc()
{
    echo "global MyFunc is called <br />";
}

const constname = "global variable is printed <br />";

$a = 'MyClass';
$obj = new $a; // prints classname::__construct
$b = 'MyFunc';
$b(); // prints funcname
echo constant('constname')."<br /><br />"; // prints global
?>
