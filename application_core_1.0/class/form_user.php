<?php

class TForm_user
{
    protected $data;
    protected $form = '';
    protected $post = array();
    protected $idForm = '';
    protected $ajax = '';
    protected $script = '';
    protected $attach = false;
    protected $style = 'form-horizontal';
    protected $d_complete;

    function __construct( $data=null, $ajax=null )
    {
        $this->data = $data;
        if($ajax) $this->ajax=$ajax;
    }

    public function beginForm($id="noid",$name="",$action="",$method="",$style="form-horizontal")
    {
        $this->form .= '<form'; 
        $this->form .= ($id)? ' id="'.$id.'"':'';
        $this->idForm = $id;
        $this->form .= ($name)? ' name="'.$name.'"':'';
        $this->form .= ($action)? ' action="'.$action.'"':'';
        $this->form .= ($method)? ' method="'.$method.'"':'';
        $this->form .=' class="'.$style.'">';
        $this->style = $style;
    }
    public function legend($legend){
        $this->form .= '<h2>'.$legend.'</h2>';
    }

    public function beginControlGroup( $label='' )
    {
        $this->form .= '<div class="control-group">'.( $label == '' ? '' : '<label class="control-label">'.$label.':</label>' ).
                            '<div class="controls">';
    }
    
    public function endForm()
    {
        $this->form .= '<div class="alert alert-info" id="alert_mess" style="display:none;"></div>';
        $this->form .= '<div class="progress" id="processBar" style="display:none;"><div class="bar" style="width: 0%;"></div></div>';
        $this->form .= '</form>';
    }
    public function hr()
    {
        $this->form .= '<hr/>';
    }
    
    public function endControlGroup()
    {
        $this->form .= '</div></div>';
    }
    public function addScript($script)
    {
        $this->script .= "\n".$script;
    }
    
    public function ckeditor( $name, $label='',$value='')
    {
        $this->post[] = "'$name',tinymce.activeEditor.getContent() ";

        $this->controls( $name, $label, '<textarea class="ckeditor" style="width:95%; height:17em;" id="'.$name.'" name="'.$name.'">'.( isset($this->data[$name]) ? $this->data[$name] : '' ).$value.'</textarea>' );
        $this->form .= "<script>
                                tinymce.init({selector:'#".$name."',language : 'ru',content_css : '/templates/temp1/style/bootstrap.min.css',
                                plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste insert_image'
    ],
    
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image insert_image | code',
                        convert_urls: false,
                        relative_urls: false,
                        remove_script_host: false,
                        element_format : 'html',
                        schema: 'html5',
                        setup: function(editor) {
                            editor.on('change', function(e) {
                                $('#$name').html(tinymce.activeEditor.getContent());
                            });
                        }

                                });
                                $('#editAct').click(function(){
                                    if(tinymce.activeEditor.isHidden()){
                                        tinymce.activeEditor.show();
                                    }else{
                                        tinymce.activeEditor.hide();
                                    }
                                });
                        </script>";
    }

    public function checkbox( $name, $label, $value )
    {
        $nameid = str_replace("[", "", str_replace("]", "", $name));
        $this->post[]= "'$name',($('#$nameid').is(':checked') ? $('#$nameid').val() : 'null') ";

        $this->form .= '<label class="checkbox">
                            <input type="checkbox" id="'.$nameid.'" name="'.$name.'" value="'.$value.'"'.( isset($this->data[$name]) && $this->data[$name] == $value ? ' checked' : '' ).'>'.$label.
                       '</label>';
    }
    public function insertField($type,$name, $label="", $value='', $id="", $required="", $placeholder="", $pattern='',$options=''){
        switch ($type){
            case "inputText":
                $this->inputText($name, $label, $value, $id, $required, $placeholder, $pattern);
            break;
            case "textarea":
                $this->textarea($name, $label, $value, $id, $required, $placeholder, $pattern);
            break;
            case "email":
                $this->inputText($name, $label, $value, $id, $required, $placeholder, $pattern,'email');
            break;
            case "phone":
                $this->inputText($name, $label, $value, $id, $required, $placeholder, "(\+?\d[- .]*){7,13}");
            break;
            case "file":
                $this->inputFile($name, $label);
            break;
            case "select":
                $this->select($name, $label, $options, $value);
            break;
            case "checkbox":
            	$this->checkbox($name, $label, $value);
            break;
        }
    }
    public function submit($label ){
        $this->form .= '<button type="submit" class="btn btn-primary">'.$label.'</button>';
    }
    public function button( $name, $label, $icon )
    {
        $this->form .= '<button class="btn btn-mini" id="'.$name.'" name="'.$name.'"><i class="'.$icon.'"></i> '.$label.'</button>';
    }
    
    public function hiddenArr( $name, $id, $value )
    {
        $this->post[]= "'$name':$('#$id').val(), ";

        $this->form .= '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" />';
    }
    
    public function hidden( $name, $value )
    {
        $this->post[]= "'$name',$('#".$this->idForm." #$name').val() ";

        $this->form .= '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />';
    }
    //поправить! не работает!
    public function typeaHead( $name, $label="", $value="", $dataProvide="", $required="", $placeholder="", $pattern="",$type="text" )
    {
        $this->post[]= "'$name',$('#$name').val() ";
        $input = '<input type="'.$type.'" id="'.$name.'" name="'.$name.'" value="'.( isset($this->data[$name]) ? $this->data[$name] : '' ).(($value)? $value:'').'"';
        $input .= ($required)? " required":"";
        $input .= ($placeholder)? ' placeholder="'.$placeholder.'"':'';
        $input .= ($pattern)? ' pattern="'.$pattern.'"':'';
        $dp="";
        if($dataProvide){
            $dp = "[";
            foreach($dataProvide as $field){
                $dp .="'".$field->name."',";
            }
            $dp = substr($dp,0,-1);
            $dp.="]";
        }
        $input .= ($dp)? 'data-provide="typeahead" data-items="4" data-source="'.$dp.'"':'';
        $input .= ' />';
        $this->script .= ($dataProvide)? "$('#$name').typeahead();":"";
        $this->controls( $name, $label, $input );
    }
    public function inputText( $name, $label="", $value='', $id="", $required="", $placeholder="", $pattern="",$type="text" )
    {
        $this->post[]= "'$name',$('#".$this->idForm." #$name').val() ";
        $input = '<input type="'.$type.'" id="'.$name.'" name="'.$name.'" value="'.( isset($this->data[$name]) ? $this->data[$name] : '' ).(($value)? $value:'').'"';
        $input .= ($required)? " required":"";
        $input .= ($placeholder)? ' placeholder="'.$placeholder.'"':'';
        $input .= ($pattern)? ' pattern="'.$pattern.'"':'';
        $input .= ' />';
        $this->controls( $name, $label, $input );
    }
    
    public function inputFile( $name, $label )
    {
        $this->post[]= "'$name',$('#".$this->idForm." #$name').get(0).files[0]";

        $this->controls( $name, $label, '<input type="file" id="'.$name.'" name="'.$name.'" />' );
        $this->html('<div class="progress progress-striped active" id="progress">
    <div class="bar" style="width: 0%;"></div>
    </div>');
        $this->attach = true;
    }
    
    public function textarea( $name, $label,$value='', $id="", $required="", $placeholder="", $pattern="" )
    {
        $this->post[]= "'$name',$('#".$this->idForm." #$name').val() ";
        
        $this->controls( $name, $label, '<textarea id="'.$name.'" name="'.$name.'" '.(($placeholder)? ' placeholder="'.$placeholder.'"':'').' '.(($pattern)? ' pattern="'.$pattern.'"':'').' '.(($required)? " required":"").' >'.( isset($this->data[$name]) ? $this->data[$name] : '' ).$value.'</textarea>' );
    }
    
    public function select( $name, $label, $option, $value="" )
    {
        $this->post[]= "'$name',$('#".$this->idForm." #$name option:selected').val() ";

        $s_option = '';
        foreach ( $option as $o )
        {
            if(is_array($o) || is_object($o)){
                $s_option .= '<option value="'.$o['value'].'" '.(($value==$o['value'])? "selected":"").' >'.$o['title'].'</option>';
            }else{
                $s_option .= '<option value="'.$o.'" '.(($value==$o)? "selected":"").' >'.$o.'</option>';
            }
        }
        $this->controls( $name, $label, '<select id="'.$name.'" name="'.$name.'">'.$s_option.'</select>' );
    }
    
    public function radio( $name, $label, $value )
    {
        $this->post[]= "'$name',($('#$name').is(':checked') ? $('#$name').val() : 'null') ";

        $this->form .= '<label class="checkbox">
                            <input type="radio" class="'.$name.'" name="'.$name.'" value="'.$value.'"'.( isset($this->data[$name]) && $this->data[$name] == $value ? ' checked' : '' ).'>'.$label.
                       '</label>';
    }
    public function html($html)
    {
        $this->form .= $html;
    }
    public function setD_complete($data) 
    { //устанавливаем контейнер для сообщения об отправки
        $this->d_complete = $data;
    }

    public function __toString()
    {
        $this->script();
        return $this->form;
    }
    
    
    protected function controls( $name, $label, $input )
    {
        $this->form .= ($this->style=="form-inline")? "":'<div class="control-group">';
        if($label == ''){
            $this->form .= $input;
        }else{
            $this->form .= '<label class="control-label" for="'.$name.'">'.$label.':</label>'. (($this->style=="form-inline")? $input:'<div class="controls">'.$input.'</div>');
            
        }
        $this->form .= ($this->style=="form-inline")? "":'</div>';
    }
    
    
    protected function get_d_get_post($obj=true){
        if($obj){
            $res = 'function d_get_post(){
                var formData = {
                ';
                foreach($this->post as $post){
                $pp = explode(",",$post);
                
                $res .= "".$pp[0].":".$pp[1].",
                        ";
                }
                $res .= "};";
        }else{
            $res = 'function d_get_post(){
                var formData = new FormData();
                ';
            foreach($this->post as $post){
                $res .= "formData.append(".$post.");
                    ";
            }
        }
        $res .= '
            return formData;
            }
            ';
        return $res;
    }
    
    protected function script()
    {
        $script = '<script>';
        
        //считываем значение всех полей
        if($this->ajax){ //если неадмин!
            $script .= $this->get_d_get_post(false);
        }else{ //если админ
            if($this->attach===true){ //если прикрепляем файл
                $script .= $this->get_d_get_post(false);
            }else{ 
                $script .= $this->get_d_get_post();
            }
        }
                            
        if($this->script){
            $script .= $this->script;
        }
        if($this->ajax){
            $script .= '$("#'.$this->idForm.'").submit(function(){ ';
            //проверяем на ошибки
            $script .= 'var err = false; var errAll = false; var form = $("#'.$this->idForm.'").get(0); 
                for ( var i = 0; i < form.length; i++ ){
                	var patt = $( form.elements[i] ).attr("pattern");
                    if ( $( form.elements[i] ).val() == "" && $( form.elements[i] ).attr("required") !== undefined)
                    {
                    	err = true;
                    	break;
                    }
                    if(patt !== undefined){
                    	if($( form.elements[i] ).val().match(patt) == null){
                    		errAll = form.elements[i];
                    		break;
                    	}
                    }
                }
                
                if (errAll !== false){
                	$(errAll).addClass("error");
                	$("#alert_mess").html("Введен неверный формат данных!");
                } 
                if (err)  {
                    $("#alert_mess").html("Все поля обязательны для заполнения");
                 }
                 if (err || errAll !== false){
                 	$("#alert_mess").show();
                    return false;
                 }
                 ';
            
            $script .= 'var params = d_get_post();
                ';
            // обход jquery
            $script .= '
                var xhr = new XMLHttpRequest();
                xhr.open( "POST", "'.$this->ajax.'", true );
                xhr.setRequestHeader("X-Requested-With", "XmlHttpRequest");
                xhr.onreadystatechange = function(){

                    if ( this.readyState === 4 ) // запрос завершён
                    {
                        if ( this.status === 200 ) 
                            d_complete(this.response);
                    }
                };
                xhr.upload.addEventListener( "progress", function(e){

                    if ( e.lengthComputable )
                    {
                        $("#processBar .bar").css("width",Math.round( e.loaded / e.total * 100 ) + "%");
                    }
                });
                xhr.send(params);
                $("#processBar").show();
            ';
            
            /*
            $this->form .= '$.post("'.$this->ajax.'",params,function(data){
                $(".forms").html(data);
            });';
             * 
             */
            $script .= ' return false; });';
            if(!empty($this->d_complete))
            {
                $script .= 'function d_complete(data)
                            {
                                $("#'.$this->idForm.' '.$this->d_complete.'").html(data);
                            };'; 
                        //
            }else{
               $script .= 'function d_complete(data)
                            {
                                $("#'.$this->idForm.'").html(data);
                            };'; 
            }
                            
        }
        $script .='</script>';
        TPages::$script = $script;
    }
}

?>