<?php
define('LOGGER', true);
define('BR', (php_sapi_name() === 'cli') ? PHP_EOL : '<br>');
define('NBS', (php_sapi_name() === 'cli') ? "\t" : '&nbsp;&nbsp;&nbsp;');

function logger($msg)
{
	$msg = php_sapi_name() === "cli" ? strip_tags($msg) : $msg;
	if(LOGGER) echo $msg;
}

class Driver
{
	public function __call($workName, $params)
	{
		logger(NBS . NBS . ' - ' . $workName . ' - ' . array_pop($params) . BR);
	}
}

$driver = new Driver();

// двигатель должен знать о кол-ве цилиндров
// по условию задачи их 4шт.
define('WORKING_CHAMBER_COUNT', 4);

// по условию задачи 4 такта
define('TACT_COUNT', 4);

function runWorkingChamberEngine()
{
	global $driver;

	for($tactNumber = 1; $tactNumber <= TACT_COUNT; $tactNumber++)
	{
		logger(NBS . '- такт ' . $tactNumber . BR);
		
		switch ($tactNumber)
		{
			case 1: // впуск
					$driver->moveInletValve('open');
					$driver->movePistone('down');
				break;
			case 2: // сжатие
					$driver->moveInletValve('close');
					$driver->movePistone('up');
				break;
			case 3: // работа
					$driver->doDetonation('boom');
					$driver->movePistone('down');
				break;
			case 4: // выпуск
					$driver->moveOutputValve('open');
					$driver->movePistone('up');
					$driver->moveOutputValve('close');
				break;
		}
	}
}

// двигатель запускается из
// положения ВМТ(верхняя мертвая точка) - первый поршень вверху
// клапаны первого цилиндра закрыты

$fuel = 10; // ограничитель

function runFourCylinderEngine()
{	
	global $fuel; // ограничитель

	logger('Cтарт двигатель' . BR . BR);

	while($fuel > 0)	// заводим двигатель
	{
		// двигатель знает свое кол-во цилиндров(рабочих камер)
		for($i = 1; $i <= WORKING_CHAMBER_COUNT; $i++)
		{
			logger('<span style="font-weight: bold">цилиндр №' . $i . '</span>' . BR);
			runWorkingChamberEngine();
		}

		$fuel--;
	}

	logger('Стоп двигатель' . BR);
}

runFourCylinderEngine(4);