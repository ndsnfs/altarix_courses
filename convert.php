<?php
// переводит десятичное в шестнадцатиричное
$myDecHex = function($dec)
{   
    $dec = (int)$dec;
    $enum = '0123456789ABCDEF';

    $stack = array();
    $sus = strlen($enum);
 
    while($dec > 0)
    {
        array_push($stack, substr($enum, ($dec % $sus), 1));
        $dec = floor($dec / $sus);
    }
 
    $result = implode('', array_reverse($stack));

    return $result;
};

// переводит шестнадцатиричное в десятичное
$myHexDec = function($hex)
{
    $enum = '0123456789ABCDEF';
    $stack = array();
	$resultSum = 0;
	$j = 0;
    
    // разложение строки на отдельные символы
	for($i = 0; $i < strlen($hex); $i++)
	{
		$char = substr($hex, $i, 1);

        // если символа нет в заданном наборе $enum выходим из программы
        $charPosInEnum = strpos($enum, $char);
        if(!$charPosInEnum && $charPosInEnum !== 0)
        {
            throw new Exception('Не 16-тиричное число');
        }

		array_push($stack, $char);
	}

	// получение символов в обратном порядке
	while($stack)
	{ 
		$last = array_pop($stack);
		$resultSum += strpos($enum, $last) * 16**$j;
		$j++;
	}

	// 1*(16**4)+15*(16**3)+0*(16**2)+8*(16**1)+13*(16**0)
	return $resultSum;
};

// возвратит функцию, кот. переводит десятичное число в какую-либо систему
function getDecFunc($intention)
{
    global $myDecHex;

    switch ($intention)
    {        
        case 16: return $myDecHex;
        // case n: return ...;
        // какие-либо др. системы
        default: throw new Exception("Invalid argument");
    }
}

// возвратит функцию, кот. переводит шестнадцатиричное число в какую-либо систему
function getHexFunc($intention)
{
    global $myHexDec;

    switch ($intention)
    {        
        case 10: return $myHexDec;
        // case n: return ...;
        // какие-либо др. системы
        default: throw new Exception("Инвалид аргумент");
    }
}

// основная функция
function conv($str, $numSustem, $intention)
{
    $str = (string)$str;
    $numSustem = (int)$numSustem;
    $intention = (int)$intention;

    switch ($numSustem)
    {
        case 10: $foo = getDecFunc($intention); break;
        case 16: $foo = getHexFunc($intention); break;
        // какие-либо др. системы
        // case n: $foo = foo(...); break;
        default: throw new Exception("Инвалид аргумент");
    }

    return $foo($str);
}

var_dump(conv('167', 10, 16));