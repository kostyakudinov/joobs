<?php

require 'phpQuery.php';
$search_job = $_POST['vacancy'];
$search_location = $_POST['location'];
$url = "https://www.work.ua/jobs-$search_location-$search_job/";

// Метод получения контента 
function getContent($url)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$result = curl_exec($curl);
	curl_close($curl);
	return $result;
}

// Метод парсер
function parser($url, $end)
{
	$start = 0;
	$host = "https://www.work.ua";
	//Парсинг н-го количества страниц
	

		$file = getContent($url);
		$doc = phpQuery::newDocument($file);
			
		foreach ($doc->find('.col-md-8 .card-hover') as $vacancy)
		{
			if ($start < $end)
			{
				$vacancy = pq($vacancy);
				//Лого компаний
				$img = $vacancy->find('.logo-img img')->attr('src');
				echo '<pre>' . "<img src='$img'>" . '</pre>';
				$title = $vacancy->find('h2 a')->attr('title');
				echo  '<pre>' . $title . '</pre>'; 
				$start++;
			}else break;
		}
			
		

		//Проверка следующей страницы
			if($start < $end)
			{
				$next = $doc->find('.pagination li:eq(6) a')->attr('href');
				if (!empty($next))
				{
					$next = $host . $next;
			    	parser($next, $end);
			    	$start++;   
				}
			}
			
}

//Счетчик страниц
$start = 0;
// Количество страниц для парсинга
$end = 10;
parser($url, $end);