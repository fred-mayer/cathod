<?php

final class TMySQLSelect implements Iterator, Countable
{
    protected $array;
    protected $position=0;
    protected $qresult=false;

    public function __construct( $query, TMySQL $mysql )
    {
        $this->qresult = $mysql->query( $query );
    }

    public function __destruct()
    {
        if ( $this->qresult !== false )
        {
            mysqli_free_result( $this->qresult );
            $this->qresult = false;
        }
    }

    public function current( $arg=null )
    {
        if ( isset( $this->array ) )
        {
            if ( is_null( $arg ) )
            {
                return $this->valid() ? new TObject( $this->array ) : false;
            }
            else
                return isset($this->array[$arg]) ? $this->array[$arg] : false;
        }
        elseif ( $this->next() )
        {
            if ( is_null( $arg ) )
            {
                return new TObject( $this->array );
            }
            else
                return isset($this->array[$arg]) ? $this->array[$arg] : false;
        }
        else
            return false;
    }

    public function next() //return bool
    {
        $this->position++;
        $this->array = mysqli_fetch_assoc( $this->qresult );
        return $this->valid();
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->array === null ? false : true;
    }

    public function rewind()
    {
        $this->position = 0;
        mysqli_data_seek( $this->qresult, $this->position );
        $this->array = mysqli_fetch_assoc( $this->qresult );
    }

    public function count()
    {
        return mysqli_num_rows( $this->qresult );
    }
    
    // Функция сортирует по сталбцу $row в алфавитном порядке
    final public function orderBy( $row ) //return TObject
    {
        while( $this->next() )
        {
            $array_row[] = $this->array[ $row ];
            $array_tmp[] = $this->array;
        }

        
        if ( !isset($array_row) ) return false;

        natcasesort( $array_row );


        foreach ( $array_row as $key => $value )
        {
            $array[]= $array_tmp[ $key ];
        }

        return new TObject( $array );
    }

    final public function toObject() //return TObject
    {
        while( $this->next() )
        {
            $array[] = $this->array;
        }
        return new TObject( $array );
    }
}

class TMySQL
{
    static private $dbh=false;
    static private $result_transaction;

    public function __construct( $location=DB_LOCATION, $user=DB_USER, $password=DB_PASSWORD, $name=DB_NAME )
    {
        if ( self::$dbh === false )
        {
            self::$dbh = mysqli_connect( $location, $user, $password, $name );
            $this->query( 'SET NAMES UTF8' );
            
            self::$result_transaction[] = true;
        }
    }

    public function __destruct()
    {
        if ( self::$dbh !== false )
        {
            mysqli_close( self::$dbh );
            self::$dbh = false;
        }
    }

    final public function query( $query ) //return дискриптор, в случаи ошибки возвращает false
    {
        $result = mysqli_query( self::$dbh, $query );
        if ( self::$result_transaction[sizeof( self::$result_transaction ) - 1] == true )
            self::$result_transaction[sizeof( self::$result_transaction ) - 1] = ( $result === false) ? false : true;

        return $result;
    }

    final public function multi_query( $query ) //return bool
    {
        return mysqli_multi_query( self::$dbh, $query );
    }

    public function insert_query( $query ) //return id, в случаи ошибки возвращает false
    {
        return ($this->query( $query ) === false) ? false : mysqli_insert_id( self::$dbh );
    }

    final public function select( $query ) //return TMySQLSelect
    {
        return new TMySQLSelect( $query, $this );
    }
    
    /*
     * $option = 'DELAYED' - работает только с таблицами MyISAM, MEMORY, ARCHIVE, и BLACKHOLE
     */
    final public function insert( $tbl_name, $values, $option='' ) // return id, в случаи ошибки возвращает false
    {
        $ins_colum = $ins_value = $comma = '';
        foreach ( $values as $col_name=>$value )
        {
            $ins_colum .= $comma.'`'.$col_name.'`';
            $ins_value .= $comma.'\''.$value.'\'';
            
            $comma = ', ';
        }
        return $this->insert_query( 'INSERT'.($option == '' ? '' : ' '.$option).' INTO '.$tbl_name.'('.$ins_colum.') VALUES('.$ins_value.')' );
    }

    final public function update( $tbl_name, $values, $where='' ) // return bool
    {
        $ins_colum = $comma = '';
        foreach ( $values as $col_name=>$value )
        {
            $ins_colum .= $comma.'`'.$col_name.'`=\''.$value.'\'';
            
            $comma = ', ';
        }

        return (boolean)$this->query( 'UPDATE '.$tbl_name.' SET '.$ins_colum.($where == '' ? '' : ' WHERE '.$where) );
    }

    //
    final public function exists( $query ) // return bool
    {
        return (boolean)$this->select( 'SELECT EXISTS('.$query.') AS `exists`' )->current('exists');
    }

    final public function beginTransaction() //return bool
    {
        return self::$result_transaction[] = mysqli_autocommit( self::$dbh, false );
    }

    /*
     * Фиксирует текущую транзакцию
     * $result = true - Фиксирует текущую транзакцию
     * $result = false - Отменяет текущею транзакцию, вызываем метод rollback()
     */
    final public function commit( $result=true ) // return bool
    {
        if ( $result == true )
        {
            $result = mysqli_commit( self::$dbh );
            mysqli_autocommit( self::$dbh, true );

            unset( self::$result_transaction[sizeof( self::$result_transaction ) - 1] );

            return $result;
        }
        else
            return $this->rollback();
    }

    // Отменяет текущею транзакцию
    final public function rollback() //return bool
    {
        $result = mysqli_rollback( self::$dbh );
        mysqli_autocommit( self::$dbh, true );
        
        unset( self::$result_transaction[sizeof( self::$result_transaction ) - 1] );
                
        return $result;

    }

    /*
     * Функция возвращает результат выполнения запросов внутри транзакции
     * Если один из запросов к БД завершится ошибкой, то функция вернет false
     */
    final public function getResultTransaction()
    {
        return self::$result_transaction[sizeof( self::$result_transaction ) - 1];
    }

    static public function real_escape_string( $escapestr )
    {
        return (self::$dbh === false) ? $escapestr : mysqli_real_escape_string( self::$dbh, $escapestr );
    }
}

?>