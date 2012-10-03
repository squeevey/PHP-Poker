<?php
	
	define('BASE_PATH', realpath( __DIR__ ));
	chdir(BASE_PATH);

	$_include_paths = array(
		'class/',
		'class/exceptions/'
	);

	$_include_path = get_include_path();
	
	foreach($_include_paths as $path)
	{
		$_include_path .= PATH_SEPARATOR . BASE_PATH . '/' . $path;
	}
	
	set_include_path($_include_path);
		
	function __autoload($className)
	{	
		$_path = $className. '.class.php';
		if($path = stream_resolve_include_path($_path))
		{	
			require_once( $path);
		}
	}
	
	
?>
	