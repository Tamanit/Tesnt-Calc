<?php
 
function fValidateDate ($Variable, $ErrorNum )
{//Валидация и инициализация даты

	$Variable = explode(".", $Variable);

	if ( (int)$Variable[0] < 1 || (int)$Variable[0] > 31 )
	{
		(int)$Variable[0] = $ErrorNum;
		exit ( json_encode($Variable[0]));
	};
	if ( (int)$Variable[1] < 1 || (int)$Variable[0] > 12 )
	{
		$Variable[1] = $ErrorNum;
		exit (json_encode( $Variable[1]));
	};
	if ( (int)$Variable[2] < 1 || (int)$Variable[2] > 9999 )
	{
		$Variable[2] = $ErrorNum;
		exit ( json_encode($Variable[2]));
	}
	return $Variable;
};

function fValidateData ($minValue, $maxValue, $Variable, $ErrorNum)
{//Валидация и инициализация данных
	if ( (int)$Variable < (int)$minValue || (int)$Variable > (int)$maxValue )
	{
		$Variable = $ErrorNum;
		exit ( json_encode($Variable));
	}
	else
	{
		$Variable = $VariablePOST;
	};
	return $Variable;
};

				//		0    1   2   3   4   5   6   7   8   9  10  11
$DaysMMod4True = Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );	
$DaysMMod4False = Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );

$startDate = $_POST["startDate"]; 
	fValidateDate ($startDate, "NotValidateData(startDate)"); 
$sum = $_POST["sum"];
	fValidateData (1000, 3000000, $sum, "NotValidateData(sum)"); 
$term = $_POST["term"];
	fValidateData (1, 60, $term, "NotValidateData(term)"); 
$percent = $_POST["percent"];
	fValidateData (3, 100, $percent, "NotValidateData(percent)"); 
$sumAdd = $_POST["sumAdd"];
	fValidateData (0, 3000000, $sumAdd, "NotValidateData(sumAdd)"); 
if ($startDate % 4 == 0)
{ //DaysY инициализация
	(int)$DaysY = (int)$DaysMMod4True[(int)$startDate[1]-1] - (int)$DaysMMod4True[0];
}
else
{
	(int)$DaysY = (int)$DaysMMod4False[(int)$startDate[1]-1] - (int)$DaysMMod4False[0];
};

for ($i = (int)$term; $i > 0; $i-- )
{
	if ((int)$startDate[2] % 4 == 0)
	{
		$sum = (real)$sum + ((int)$sum + (int)$sumAdd) *  (((int)$DaysY * (int)$percent) / 365);
		(int)$startDate[1] = (int)$startDate[1] + 1;
		if ((int)$startDate[1] == 13)
		{
			(int)$startDate[1] = 1;
			(int)$startDate[2]++;
		};
		(int)$DaysY = (int)$DaysMMod4True[(int)$startDate[1] - 1];
		
	}
	else
	{
		$sum = (real)$sum + ((int)$sum + (int)$sumAdd) * (int)$DaysY * (((int)$DaysY * (int)$percent) / 366);
		(int)$startDate[1] = (int)$startDate[1] + 1;
		if ((int)$startDate[1] == 13)
		{
			(int)$startDate[1] = 1;
			(int)$startDate[2]++;
		};
		(int)$DaysY = (int)$DaysMMod4False[$startDate[1] - 1];
	};
	
};
echo json_decode ($sum);
?>
