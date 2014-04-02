<?php

    ini_set('display_errors', 1);
    error_reporting (E_ALL);


define( 'CATHOD_URL', 'http://dev1.cathod.ru/' );
define( 'CATHOD_ZIP', 'cathod.zip' );


// Функция скачивания удаленного файла на сервер
function curl_download($url, $file)
{
    $dest_file = fopen( $file, "w" );

    $resource = curl_init();

    curl_setopt( $resource, CURLOPT_URL, $url );
    //curl_setopt( $resource, CURLOPT_FILE, $dest_file );
    curl_setopt( $resource, CURLOPT_HEADER, 0 );
    curl_setopt( $resource, CURLOPT_RETURNTRANSFER, true );
    
    $content = curl_exec( $resource );

    $info = curl_getinfo( $resource );
    
    //var_dump($info);
    
    fwrite( $dest_file, $content );
    
    curl_close( $resource );
    fclose( $dest_file );
    
    return $info['http_code'] == 200 ? true : false;
}

function set( $set, $config )
{
    foreach ( $set as $name => $value )
    {
        $pattern[] = "/define\(\s+\'".$name."'\,\s+\'(\w+)\'\s+\)\;/i";
        $replacement[] = "define( '".$name."', '".$value."' );";
    }
    
    return preg_replace( $pattern, $replacement, $config );
}

function install()
{
    global $error;

    $zip = new ZipArchive();
  
    if ( $zip->open( CATHOD_ZIP ) === true )
    {
        $zip->extractTo( '../'.$_SERVER["SERVER_NAME"] );
        $zip->close();
    }
    else
    {
        $error = 'Не удалось распаковать установочный архив '.CATHOD_ZIP;
    }

    $config = file_get_contents( 'config.php' );
    
    $set = array( 'DB_NAME'=>$_POST['DB_NAME'],  'DB_USER'=>$_POST['DB_USER'], 'DB_PASSWORD'=>$_POST['DB_PASSWORD'], 'DB_LOCATION'=>$_POST['DB_LOCATION'] );
    
    $config = set( $set, $config );
    
    if ( !file_put_contents( 'config.php', $config ) )
    {
        $error = 'Не удалось сохранить изменения в config.php';
    }


    define( 'APPLICATION_CORE', 'application_core_1.0' );
    

    include_once( 'config.php' );
    include_once( CLASS_DIR.'mysql.php');
    
    $db = new TMySQL();
    
    if ( mysqli_connect_error() )
    {
        $error = 'Ошибка подключения к БД ('.mysqli_connect_errno().')';
    }
    else
    {
        //$db->multi_query( file_get_contents( 'import_base.sql' ) ); //!!!!!!!!!!!
        $db->multi_query( file_get_contents( 'import_base.sql' )
        ."\n\nINSERT INTO `users` (`id`, `login`, `email`, `password`, `hash`, `date`) VALUES(1, 'admin', '".$_POST['ADMIN_EMAIL']."', '".md5($_POST['ADMIN_PASSWORD1'])."', 'd41d8cd98f00b204e9800998ecf8427e', '2014-04-01 12:19:21');" ); //!!!!!!!!!!!

        //$db->insert( 'users', array( 'login'=>'admin', 'email'=>$_POST['ADMIN_EMAIL'], 'password'=>md5($_POST['ADMIN_PASSWORD1']), 'hash'=>md5('') ) );

        unlink( CATHOD_ZIP );
        unlink( 'import_base.sql' );
        unlink( 'installer.php' );
        
        header( 'Location: '.SERVER_NAME.'/' );
        exit();
    }
    
}

global $error;

if ( isset($_POST['install']) )
{
    if ( $_POST['DB_LOCATION'] != '' && $_POST['DB_USER'] != '' && $_POST['DB_PASSWORD'] != '' 
            && $_POST['DB_NAME'] != '' && $_POST['ADMIN_EMAIL'] != '' && $_POST['ADMIN_PASSWORD1'] != '' 
            && $_POST['ADMIN_PASSWORD2'] != '' )
    {
        if ( $_POST['ADMIN_PASSWORD1'] == $_POST['ADMIN_PASSWORD2'] )
        {
            // Если нет установочного архива то загружаем последнию версию
            if ( !file_exists( CATHOD_ZIP ) )
            {
                if ( curl_download( CATHOD_URL.'ajax/admin?action=import&skey=1q2w3e4r5t', CATHOD_ZIP ) )
                {
                    // Выполняем установку
                    install();
                }
                else
                {
                    $error = 'Не удалось загрузить установочный архив '.CATHOD_ZIP;
                }
            }
            else
            {
                // Выполняем установку
                install();
            }
        }
        else
        {
            $error = 'Пароль администратора должен совподать!';
        }
    }
    else
    {
        $error = 'Все поля обязательны для заполнения!';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>cathod - Установка</title>

<link href="<?php echo CATHOD_URL; ?>templates/temp1/style/bootstrap.min.css" rel="stylesheet">

<link href="<?php echo CATHOD_URL; ?>templates/temp1/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="content container row">
        <h2>cathod - Установка</h2>
<?php
        if ( $error != '' )
        {
?>
        <p class="text-error"><?php echo $error; ?></p>
<?php
        }
?>
        <form name="form-installer" action="installer.php" method="post"  role="form">
            <h4>Конфигурация базы данных</h4>
            
            <div class="form-group">
                <label for="DB_LOCATION">Название хоста (обычно localhost)</label>
                <input type="text" class="form-control" name="DB_LOCATION" value="<?php echo isset($_POST['DB_LOCATION']) ? ($_POST['DB_LOCATION'] == '' ? 'localhost' : $_POST['DB_LOCATION']) : 'localhost'; ?>">
            </div>
            
            <div class="form-group">
                <label class="control-label" for="DB_USER">Имя пользователя</label>
                <input type="text" class="form-control" name="DB_USER" value="<?php echo isset($_POST['DB_USER']) ? $_POST['DB_USER'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="control-label" for="DB_PASSWORD">Пароль</label>
                <input type="password" class="form-control" name="DB_PASSWORD" value="<?php echo isset($_POST['DB_PASSWORD']) ? $_POST['DB_PASSWORD'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="control-label" for="DB_NAME">Имя базы данных</label>
                <input type="text" class="form-control" name="DB_NAME" value="<?php echo isset($_POST['DB_NAME']) ? $_POST['DB_NAME'] : ''; ?>">
                
                <p class="help-block">Имя пользователя, Пароль и Имя БД должны уже существовать для той БД, которую Вы собираетесь использовать.</p>
            </div>
            
            
            <h4>Email и пароль администратора</h4>
            
            <div class="form-group">
                <label for="ADMIN_EMAIL">Email</label>
                <input type="text" class="form-control" name="ADMIN_EMAIL" value="<?php echo isset($_POST['ADMIN_EMAIL']) ? $_POST['ADMIN_EMAIL'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="control-label" for="ADMIN_PASSWORD1">Пароль</label>
                <input type="password" class="form-control" name="ADMIN_PASSWORD1" value="<?php echo isset($_POST['ADMIN_PASSWORD1']) ? $_POST['ADMIN_PASSWORD1'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="control-label" for="ADMIN_PASSWORD2">Подтверждение пароля</label>
                <input type="password" class="form-control" name="ADMIN_PASSWORD2" value="<?php echo isset($_POST['ADMIN_PASSWORD2']) ? $_POST['ADMIN_PASSWORD2'] : ''; ?>">
            </div>


            <button type="submit" name="install" class="btn btn-primary">Установить</button>
        </form>
    
    </div>
</body>
</html>