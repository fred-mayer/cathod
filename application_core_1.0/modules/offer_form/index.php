<?php

class Toffer_form extends TModule
{
    public function display( TTemplate $template )
    {
        $params = $this->getParams();
        if ( is_array($params) ) extract( $params );

        if ( !isset($category) && isset($template->route[1]) ) // если категория не задана берем из url
        {
            $category = $template->route[1]; 
        }

        if ( !isset($idoffer) && isset($template->route[3]) )  // если $idoffer не задана берем из url
        {
            $idoffer = $template->route[3];
        }

        // Получаем все поля из базы
        $this->data['url_form'] = $template->db->select( 'SELECT url_form FROM offers WHERE id='.$idoffer )->current('url_form');

        // Получаем все поля из базы
        /*$this->data['input'] = $template->db->select( 'SELECT input.* FROM offer_'.$category.'_form offer, form_input input 
                WHERE offer.idoffer='.$idoffer.' AND offer.idform_input=input.id ORDER BY offer.level' );*/

        parent::display( $template );
    }
    
    public function beginForm()
    {
        $params = $this->getParams();
        if ( is_array($params) ) extract( $params );
?>
        <form
<?php 
            if ( isset($form_action) )
                echo 'action="'.$form_action.'" ';

            if ( isset($form_method) )
                echo 'method="'.$form_method.'" ';

            if ( isset($form_name) )
                echo 'name="'.$form_name.'" ';
?>
               />
<?php
    }
    
    public function endForm()
    {
?>
        </form>
<?php
    }
    
    public function getInput( $date )
    {
        if ( $date->input == 'input' )
        {
?>
        <label for="<?php echo $date->name; ?>"><?php echo $date->label; ?></label>
        <input type="<?php echo $date->type; ?>" id="<?php echo $date->name; ?>" name="<?php echo $date->name; ?>" 
<?php 
            if ( $date->placeholder != '' )
                echo 'placeholder="'.$date->placeholder.'" ';
            
            if ( $date->pattern != '' )
                echo 'pattern="'.$date->pattern.'" ';
            
            if ( $date->required == 1 )
                echo 'required ';
            
            if ( $date->class != '' )
                echo 'class="'.$date->class.'" ';
?>
               />
<?php
        }
        elseif ( $date->input == 'select' )
        {

?>
        <label for="<?php echo $date->name; ?>"><?php echo $date->label; ?></label>
        <select id="<?php echo $date->name; ?>" name="<?php echo $date->name; ?>" 
<?php
            if ( $date->required == 1 )
                echo 'required ';

            if ( $date->class != '' )
                echo 'class="'.$date->class.'" ';
?>
               >
<?php
            echo $date->value;
?>
        </select>
<?php
        }
        elseif ( $date->input == 'textarea' )
        {
?>
        <label for="<?php echo $date->name; ?>"><?php echo $date->label; ?></label>
        <textarea id="<?php echo $date->name; ?>" name="<?php echo $date->name; ?>" 
<?php 
            if ( $date->placeholder != '' )
                echo 'placeholder="'.$date->placeholder.'" ';

            if ( $date->required == 1 )
                echo 'required ';

            if ( $date->class != '' )
                echo 'class="'.$date->class.'" ';
?>
               ></textarea>
<?php
        }
    }
}

?>