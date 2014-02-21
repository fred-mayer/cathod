<?php

class TAdmin_admin extends TPage_admin
{
    public function exitadmin(){
	    
	    $this->db->update("users",array("hash"=>""),"login='".$_COOKIE["login"]."'");
	    setcookie ("login", "", time() - 3600);
	    setcookie ("hash", "", time() - 3600);
    }
}

?>