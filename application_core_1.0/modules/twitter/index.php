<?php

class Ttwitter extends TModule
{
    public function display( TTemplate $template )
    {
        if ( $template->route[0] == 'post' && $this->set_pos == 'section' ) // Главный модуль
        {
            if ( !empty($template->route[1]) )
            {
                $this->data = $template->db->select( 'SELECT p.id, t.id AS idpost, p.name, p.nickname, p.img, t.post FROM twitter_profile p, twitter t 
                                                        WHERE p.id=t.idtwitter_profile AND t.id='.intval($template->route[1]) )->current();
                
                if ( $this->data === false )
                {
                    $template->_404();
                }
            }
            else
            {
                $template->_404();
            }
        }
        else
        {
            /*$profile = $template->db->select( 'SELECT COUNT(*) AS count, p.id FROM twitter_profile p, twitter t WHERE t.idtwitter_profile=p.id GROUP BY p.id' );

            $idtwitter_profile = $template->db->select( 'SELECT COUNT(*) AS count, p.id FROM twitter_profile p, twitter t WHERE t.idtwitter_profile=p.id GROUP BY p.id LIMIT '.rand(0, count($profile)-1).', 1' )->current('id');


            $count_twitter = $template->db->select( 'SELECT COUNT(*) AS count FROM twitter WHERE idtwitter_profile='.$idtwitter_profile )->current('count');

            $this->data = $template->db->select( 'SELECT p.id, t.id AS idpost, p.name, p.nickname, p.img, t.post FROM twitter_profile p, twitter t 
                                                    WHERE p.id=t.idtwitter_profile AND t.idtwitter_profile='.$idtwitter_profile.' 
                                                        LIMIT '.rand(0, $count_twitter-1).', 1' )->current();*/
            
            if ( !isset($_SESSION['twitter_rand']) ) $_SESSION['twitter_rand'] = array();

            $count = $template->db->select( 'SELECT COUNT(*) AS count FROM twitter' )->current('count');
            
            $max_twitter_rand = $count > 180 ? 180 : $count;

            $twitter_rand = count($_SESSION['twitter_rand']) > $max_twitter_rand ? array() : $_SESSION['twitter_rand'];

            $rand = rand(0, $count-1);
            
            while ( isset($twitter_rand[$rand]) )
            {
                $rand = rand(0, $count-1);
            }
            
            
            $this->data = $template->db->select( 'SELECT p.id, t.id AS idpost, p.name, p.nickname, p.img, t.post FROM twitter_profile p, twitter t 
                                                    WHERE p.id=t.idtwitter_profile LIMIT '.$rand.', 1' )->current();
            
            
            $twitter_rand[$rand] = '0';
            
            $_SESSION['twitter_rand'] = $twitter_rand;
        }

        parent::display( $template );
    }

    public function getAdminToolbar( $attr )
    {
        //$buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'', 'title'=>'');
        
        //return parent::getAdminToolbar( $attr, $buttons );
        return true;
    }
    
    public function getRandPost( $get=null, $post=null )
    {
        $this->display( $this->template );
    }
}

?>