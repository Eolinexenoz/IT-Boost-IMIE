<?php

define('DS', DIRECTORY_SEPARATOR);

function currentPath($fileName)
{
    $path = array
    (
        __DIR__,
        $fileName
    );
    return implode(DS, $path);
}

function list_user()
{
	/*- Le format de la liste doit être la suivante :
 * | id | USERNAME | EMAIL                | NOTE |
 * | 1  | John     | john.doe@domaine.tld | 10   |
 * | 2  | jane     | j.doe@domaine.tld    | 20   |*/
	$path = currentPath('config.ini');
	if(file_exists($path))
	{
	    $strings = array();
	    $resource = fopen($path, 'r');
	    if($resource)
	    {
	        while (($string = fgets($resource, 4096)) !== false)
	        {
	            $strings[] = trim($string);
	        }
	        fclose($resource);
	    }
	    $config = array();
	    foreach ($strings as $string)
	    {
	        $paramsExploded = explode('=', $string);
	        $config[$paramsExploded[0]] = $paramsExploded[1];
	    }
	}
	extract($config);
	$pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $passwd); //Connexion a la bdd
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = sprintf("SELECT * FROM users");
	$stmt = $pdo->query($sql);
	$nUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//printf("| id | USERNAME | EMAIL | NOTE |\n");
	$marge_name = 0;
	$marge_email = 0;
	$marge_id = 0;
	$marge_note = 0;
	foreach ($nUsers as $index => $value)
	{
		if ($d = strlen($nUsers[$index]['username']))
			if ($d > $marge_name)
				$marge_name = $d;
		if ($d = strlen($nUsers[$index]['email']))
			if ($d > $marge_email)
				$marge_email = $d;
		if ($d = strlen($nUsers[$index]['id']))
			if ($d > $marge_id)
				$marge_id = $d;
		if ($d = strlen($nUsers[$index]['note']))
			if ($d > $marge_note)
				$marge_note = $d;
	}
	$str_marge_id = "";
	$str_marge_name = "";
	$str_marge_email = "";
	$str_marge_note = "";
	for ($i = $marge_email; $i != 0; $i--)
		$str_marge_email .= ' ';
	for ($i = $marge_name; $i != 0; $i--)
		$str_marge_name .= ' ';
	for ($i = $marge_id; $i != 0; $i--)
		$str_marge_id .= ' ';
	for ($i = $marge_note; $i != 0; $i--)
		$str_marge_note .= ' ';
	foreach ($nUsers as $index => $value)
	{
		printf($str_marge_id .= '|' . $nUsers[$index]['id'] . '|');
		printf($str_marge_name .= '|' . $nUsers[$index]['username'] . '|');
		printf($str_marge_email .= '|' . $nUsers[$index]['email'] . '|');
		printf($str_marge_note .= '|' . $nUsers[$index]['note'] . '|');
		printf("\n");
	}
}

function user_average()
{
	$path = currentPath('config.ini');
	if(file_exists($path))
	{
	    $strings = array();
	    $resource = fopen($path, 'r');
	    if($resource)
	    {
	        while (($string = fgets($resource, 4096)) !== false)
	        {
	            $strings[] = trim($string);
	        }
	        fclose($resource);
	    }
	    $config = array();
	    foreach ($strings as $string)
	    {
	        $paramsExploded = explode('=', $string);
	        $config[$paramsExploded[0]] = $paramsExploded[1];
	    }
	}
	extract($config);
	$pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $passwd); //Connexion a la bdd
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = sprintf("SELECT SUM(note) FROM users");
	$tot_id = sprintf("SELECT COUNT(id) FROM users");
	$stmt = $pdo->query($sql);
	$stmt2 = $pdo->query($tot_id);
	$note = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$note_id = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	$tot_note = (int)$note[0]['SUM(note)'];
	$tot_note_id = (int)$note_id[0]['COUNT(id)'];
	$avg_note = $tot_note / $tot_note_id;
	var_dump(sprintf("La moyenne des notes des utilisateurs est de %f", $avg_note));
}

function update_user_on_db_by_id($tab_user)
{
	$path = currentPath('config.ini');
	if(file_exists($path))
	{
	    $strings = array();
	    $resource = fopen($path, 'r');
	    if($resource)
	    {
	        while (($string = fgets($resource, 4096)) !== false)
	        {
	            $strings[] = trim($string);
	        }
	        fclose($resource);
	    }
	    $config = array();
	    foreach ($strings as $string)
	    {
	        $paramsExploded = explode('=', $string);
	        $config[$paramsExploded[0]] = $paramsExploded[1];
	    }
	}
	extract($config);
	try
	{
	    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $passwd); //Connexion a la bdd
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    if ($tab_user)
	    {
	        // On compte le nombre d'email existant en base de données
	        $query = sprintf("SELECT count(id) as n FROM users WHERE id = '%d'", $tab_user['id']);
	        $stmt = $pdo->query($query);
	        $nUsers = $stmt->fetch(PDO::FETCH_ASSOC);
	        // Update l'utilisateur en base de données si existant
	        if($nUsers['n'] != 0)
	        {
	            $sql = sprintf("UPDATE users SET note = (%d) WHERE id = (%d)", $tab_user['note'], $tab_user['id']);
	            $pdo->exec($sql);
	            var_dump(sprintf("Last insert id: %s", $pdo->lastInsertId()));
	            echo "La note de l'utilisateur a bien ete mise a jour !\n";
			}
	        else
	        {
	            $err = sprintf("L'utilisateur %s n'existe pas dans la bdd", $tab_user['id']);
	            var_dump($err);
	            echo "La maj n'a pas pu se faire\n";
	        }
	    }
	}
	catch (PDOException $e)
	{
	    var_dump($e->getMessage());
	}
	finally
	{
		$pdo = NULL;
	}
}

function update_user_on_db_by_email($tab_user)
{
	$path = currentPath('config.ini');
	if(file_exists($path))
	{
	    $strings = array();
	    $resource = fopen($path, 'r');
	    if($resource)
	    {
	        while (($string = fgets($resource, 4096)) !== false)
	        {
	            $strings[] = trim($string);
	        }
	        fclose($resource);
	    }
	    $config = array();
	    foreach ($strings as $string)
	    {
	        $paramsExploded = explode('=', $string);
	        $config[$paramsExploded[0]] = $paramsExploded[1];
	    }
	}
	extract($config);
	try
	{
	    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $passwd); //Connexion a la bdd
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    if ($tab_user)
	    {
	        // On compte le nombre d'email existant en base de données
	        $query = sprintf("SELECT count(id) as n FROM users WHERE email = '%s'", $tab_user['email']);
	        $stmt = $pdo->query($query);
	        $nUsers = $stmt->fetch(PDO::FETCH_ASSOC);
	        // Update l'utilisateur en base de données si existant
	        if($nUsers['n'] != 0)
	        {
	            $sql = sprintf("UPDATE users SET note = (%d)", $tab_user['note']);
	            $pdo->exec($sql);
	            var_dump(sprintf("Last insert id: %s", $pdo->lastInsertId()));
	            echo "La note de l'utilisateur a bien ete mise a jour !\n";
			}
	        else
	        {
	            $err = sprintf("L'utilisateur %s n'existe pas dans la bdd", $tab_user['email']);
	            var_dump($err);
	        }
	    }
	}
	catch (PDOException $e)
	{
	    var_dump($e->getMessage());
	}
	finally
	{
		$pdo = NULL;
	}
}

function add_user_to_db($tab_user)
{
	$path = currentPath('config.ini');
	if(file_exists($path))
	{
	    $strings = array();
	    $resource = fopen($path, 'r');
	    if($resource)
	    {
	        while (($string = fgets($resource, 4096)) !== false)
	        {
	            $strings[] = trim($string);
	        }
	        fclose($resource);
	    }
	    $config = array();
	    foreach ($strings as $string)
	    {
	        $paramsExploded = explode('=', $string);
	        $config[$paramsExploded[0]] = $paramsExploded[1];
	    }
	}
	extract($config);

	try
	{
	    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $passwd);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    if ($tab_user)
	    {
	        // On compte le nombre d'email existant en base de données
	        $query = sprintf("SELECT count(id) as n FROM users WHERE email = '%s'", $tab_user['email']);
	        $stmt = $pdo->query($query);
	        $nUsers = $stmt->fetch(PDO::FETCH_ASSOC);
	        // Inserer l'utilisateur en base de données si non existant
	        if($nUsers['n'] == 0)
	        {
	            $sql = sprintf("INSERT INTO users (username, email, note) VALUES ('%s', '%s', '%s')", $tab_user['username'], $tab_user['email'], $tab_user['note']);
	            $pdo->exec($sql);
	            var_dump(sprintf("Last insert id: %s", $pdo->lastInsertId()));
	            echo "L'utilisateur a bien ete ajoute !\n";
	        }
	        else
	        {
	            var_dump(sprintf("L'utilisateur '%s' est déjà existant.", $tab_user['email']));
	        }
	    }
	}
	catch (PDOException $e)
	{
	    var_dump($e->getMessage());
	}
	finally
	{
		$pdo = NULL;
	}
}

function get_user($argc, $argv) //Recup user depuis av
{
	if ($argc == 4)
	{
		$tab = array("username" => "","email" => "", "note" => "");
		$tmp = $argv[1]; //Recup username
		$tmp2 = $argv[2]; //Recup email
		$tmp3 = $argv[3]; //Recup note
		//Cat username
		$tab['username'] = strstr($tmp, '=');
		$tab['username'] = str_replace('=', '', $tab['username']);
		//Cat email
		$tab['email'] = strstr($tmp2, '=');
		$tab['email'] = str_replace('=', '', $tab['email']);
		//Cat note
		$tab['note'] = strstr($tmp3, '=');
		$tab['note'] = str_replace('=', '', $tab['note']);
		add_user_to_db($tab);
	}
	else if ($argc == 3)
	{
		if (($argv[1][1] == 'e' && $argv[1][3] == '=')|| $argv[1][2] == 'e')
		{
			$tab = array("email" => "", "note" => "");
			$tmp = $argv[1]; //Recup email
			$tmp2 = $argv[2]; //Recup new note
			//Cat email
			$tab['email'] = strstr($tmp, '=');
			$tab['email'] = str_replace('=', '', $tab['email']);
			//Cat note
			$tab['note'] = strstr($tmp2, '=');
			$tab['note'] = str_replace('=', '', $tab['note']);
			update_user_on_db_by_email($tab);
		}				
		else if ($argv[1][2] == 'i')
		{
			$tab = array("id" => "", "note" => "");
			$tmp = $argv[1]; //Recup id
			$tmp2 = $argv[2]; //Recup new note
			//Cat email
			$tab['id'] = strstr($tmp, '=');
			$tab['id'] = str_replace('=', '', $tab['id']);
			//Cat note
			$tab['note'] = strstr($tmp2, '=');
			$tab['note'] = str_replace('=', '', $tab['note']);
			update_user_on_db_by_id($tab);
		}
		else
			echo "Veuillez entrer les donnes tel que :\nphp add_user.php --email=EMAIL --note=NOTE_INTEGER\n\nou tel que :\nphp add_user.php -e=EMAIL -n=NOTE_INTEGER\n\nou encore :\nphp add_user.php --id=INTEGER -n=NOTE_INTEGER\n";
	}
	else if ($argc == 2)
	{
		if ($argv[1][1] == 'l')
			list_user();
		else if ($argv[1][1] == 'a')
			user_average();
		else if ($argv[1][2] == 'l')
			list_user();
		else if ($argv[1][2] == 'a')
			user_average();
		else
			sprintf("Veuillez entrer les donnees telle que :\nphp add_user.php --list\nou :\nphp add_user.php -l\t(pour lister les users)\nou :\nphp add_user.php --average\nou :\nphp add_user.php -a\t(pour voir la moyenne de note des users)");
	}
}

//echo "Veuillez entrer les donnes tel que :\nphp add_user.php --username=USERNAME --email=EMAIL --note=NOTE_INTEGER\nou tel que :\nphp add_user.php -u=USERNAME -e=EMAIL -n=NOTE_INTEGER";
get_user($argc, $argv);