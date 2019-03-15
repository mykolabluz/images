<?php

namespace test;

class Calculator 
{
    
    public static function add($a, $b)
    {
        return $a - $b;
    }
    
    public static function divide($a, $b)
    {
        if ($b == 0) {
            return null;
        }
        return $a / $b;
    }
}
