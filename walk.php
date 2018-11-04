<?php
define('NLN', '<br>'); // new ilne

/* common func */

function strpos_array($haystack, $needles)
{
    if ( is_array($needles) ) {
        foreach ($needles as $str) {
            if ( is_array($str) ) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = mb_strpos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return mb_strpos($haystack, $needles);
    }
}

function hasWarningWord($msg)
{
	return strpos_array($msg, array('холодно', 'заморозки', 'замерзла'));
}

function isLapseOfTemperature($y, $n, $f)
{
	return $y > $n && $n > $f;
}

/* common func END */

// основная функция
function estimateTheWeather($yesterdayTemperature, $nowTemperature, $theOtherDayTemperature, $wetStuff, $AnyaMsg)
{
	// привести типы
	$yesterdayTemperature = (int)$yesterdayTemperature;
	$nowTemperature = (int)$nowTemperature;
	$theOtherDayTemperature = (int)$theOtherDayTemperature;
	$wetStuff = (bool)$wetStuff;
	$AnyaMsg = (string)$AnyaMsg;

	// итоговая фраза
	$result = '';

	// если снижение тем-ры три для подряд,
	// и аня употребит определенное слово,
	// и завтра температура упадет на 5град.
	if(isLapseOfTemperature($yesterdayTemperature, $nowTemperature, $theOtherDayTemperature)
		&& hasWarningWord($AnyaMsg)
		&& $nowTemperature - $theOtherDayTemperature > 5)
	{
		return 'ты не пройдешь!'; // выходим из функции
	}

	if($nowTemperature < 13)
	{	
		if($yesterdayTemperature >= 11 && $theOtherDayTemperature >= 11)
		{
			// если температура не опускалась ни вчера ни завтра не будет опускаться меньше 11
			$result .= '- одень шапку.' . NLN;
		}
		else if($yesterdayTemperature < 11 || $theOtherDayTemperature < 11)
		{
			// если температура вчера или завтра меньше 11
			$result .= '- одень зимнюю шапку.' . NLN;
		}
	}

	// если снижение тем-ры три для подряд(вчера,сегодня, завтра), или аня употребит определенное слово
	if(isLapseOfTemperature($yesterdayTemperature, $nowTemperature, $theOtherDayTemperature) || hasWarningWord($AnyaMsg))
	{
		$result .= '- ты хорошо оделся?' . NLN;
	}

	// если осадки
	if($wetStuff)
	{
		$result .= '- и возьми с собой зонтик!' . NLN;

		// если в завтра температура упадет более чем на три градуса
		if($nowTemperature - $theOtherDayTemperature > 3)
		{
			$result .= ' - и шарф.' . NLN;
		}
	}

	return $result;
}

echo estimateTheWeather(12,11,4,true, 'tr замерзла ёмае');