<?php

function search($text, $pg_id){
	
	
	if(iconv_strlen($text,'UTF-8') > 2){
	$db = new PDO("mysql:host=localhost;dbname=films", 'wpadmin', '12345');
	$text = htmlspecialchars($text);
	$pg_id = (int)$pg_id; //для пагинации
	$data_array = array(); //все результаты поиска помещаются в этот массив

//Actors, Directors
	$get_name = $db->prepare("(SELECT first_name, last_name FROM actors WHERE first_name 
	LIKE concat('%', ?, '%') OR last_name LIKE concat('%', ?, '%')) 
	UNION (SELECT first_name, last_name FROM directors WHERE first_name 
	LIKE concat('%', ?, '%') OR last_name LIKE concat('%', ?, '%'))");
	
	$get_name -> execute(array($text, $text,$text, $text));
	$names = $get_name->fetch(PDO::FETCH_ASSOC);
	if($names['first_name'] !== null || $names['last_name'] !== null)
	{
		array_push($data_array,$names['first_name'],$names['last_name']);
		// echo '<a href="">'.$names['first_name'].'</a>';
		// echo '<a href="">'.$names['last_name'].'</a>';
		// echo '<a href="">'.$names['gnr'].'</a>';
	}

//Genre
		$get_name = $db->prepare("SELECT genre 
		FROM directors_genres WHERE genre 
		LIKE concat('%', ?, '%')");
		$get_name -> execute(array($text));
		$names = $get_name->fetch(PDO::FETCH_ASSOC);

		if($names['genre']!==null)
		array_push($data_array,$names['genre']);
		//echo '<a href="">'.$names['genre'].'</a>';

//Movies
		$get_name = $db->prepare("SELECT name 
		FROM movies WHERE name 
		LIKE concat('%', ?, '%')");
		$get_name -> execute(array($text));
		$names = $get_name->fetch(PDO::FETCH_ASSOC);
			
		if($names['name']!==null)
		array_push($data_array,$names['name']);
		//echo '<a href="">'.$names['name'].'</a>';
		

		if(count($data_array)!==null)
		{
			if($pg_id == null )
			{
			$pg_id = (int) 1;
			for($i = 0; $i < $pg_id * 2; $i++)
			{
			echo ' <li><a href="">'.$data_array[$i].'</a> <li>';
			}
		}   else{
			if(count($data_array) > $pg_id * 2)
			
				for($i = $pg_id; $i < $pg_id * 2; $i++)
				echo ' <li><a href="">'.$data_array[$i].'</a> <li>';
			}
			$pg_id++;
			echo '<a id="gpl" pg="'.$pg_id.'" href="">Страница '.$pg_id.'</a>"';
		}




		$db = null;


	}else
	$db = null;
}

search($_POST['txt'],$_POST['pagination_id']);
?>