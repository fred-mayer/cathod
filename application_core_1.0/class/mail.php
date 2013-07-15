<?php

class TMail
{
    protected $toAdress = array();
    protected $sender = "";
    protected $toCC = array();
    protected $toBCC = array();
    protected $subject = "";
    protected $isHTML = false;
    protected $content = "";
    protected $errors = false;
    protected $encoding = "utf-8";
    protected $unic = "";
    protected $attach = array();
            
    function __construct(){
        $this->unic = '----'.substr(md5(uniqid(rand(),true)),0,16);
    }
    
    public function send(){
        //кому
        if(count($this->toAdress)>0){
            $toS = "";
            foreach($this->toAdress as $to):
                if($this->ValidateAddress($to)){
                    $toS .= "<".$to.">,";
                }else{
                    $error[]="Один или несколько адресов получателей не являются email адресом";
                    break;
                }
            endforeach;
            $toS = substr($toS, 0, -1); //удалили последнюю запятую
        }else{ $error[]="Должен быть установлен хотя бы один получатель";}
        
        //тема
        if(!$this->subject){ $error[]="Не указана тема письма";}
        
        //контент
        if(!$this->content){ $error[]="Нет содержания письма";}
        
        $headers ="";
        
        //от кого
        $headers .= ($this->sender && $this->ValidateAddress($this->sender))? "From: <".$this->sender.">\r\n":"";
        //копия
        if(count($this->toCC)>0){
            $headers .= "Cc: ";
            foreach($this->toCC as $cc):
                $headers .= "<".$cc.">,";
            endforeach;
            $headers = substr($headers, 0, -1);
            $headers .= "\r\n";
        }
        //скрытая копия
        if(count($this->toBCC)>0){
            $headers .= "Bcc: ";
            foreach($this->toBCC as $bcc):
                $headers .= "<".$bcc.">,";
            endforeach;
            $headers = substr($headers, 0, -1);
            $headers .= "\r\n";
        }
        //заголовки
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$this->unic."\""; //задаем уникальный разделитель
        $body = "";
        $body .= "--".$this->unic."\r\n";
        $body .= ($this->isHTML)? "Content-Type: text/html; charset=".$this->encoding."\r\n":"Content-Type:text/plain; charset=".$this->encoding."\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $this->content = $body .chunk_split(base64_encode($this->content))."\r\n";
        
        //Вложения
        if(count($this->attach)){
            foreach ($this->attach as $attach){
                $addContent = "--".$this->unic."\r\n";
                $addContent .= "Content-Type: ".$attach['type']."; name=\"".$attach['name']."\"\r\n";
                $addContent .= "Content-Transfer-Encoding:base64\r\n";
                $addContent .= "Content-Disposition:attachment; filename=\"".$attach['name']."\"\r\n\r\n";
                $addContent .= $attach['file']."\r\n";
            }
            $this->content .= $addContent;
        }
        $this->content .= "--".$this->unic."--";
        
        //все готово проверяем были ли ошибки если нет то отправляем
        if(isset($errors)){
            return $errors;
        }else{
            //echo $headers."<br>\r\n".$this->content;
            return mail($toS, $this->subject, $this->content, $headers);
        }
        
    }
    public function addAttach($file){
        $this->attach[]=array('name'=>$_FILES[$file]['name'],'type'=>$_FILES[$file]['type'],'file'=>chunk_split(base64_encode(file_get_contents($_FILES[$file]['tmp_name']))));
    }
    
    public function addRecipient($recipient){
        if(is_array($recipient)){ //если задано массивом
            foreach($recipient as $to){
                $to = $this->cleanLine($to); //очищаем лишнее
                $this->toAdress[] = $to;
            }
        }else{
            $to = $this->cleanLine($recipient);
            $this->toAdress[] = $to;
        }
    }
    
    protected function cleanLine($value){
        return trim(preg_replace('/(%0A|%0D|\n+|\r+)/i', '', $value));
    }
    
    //очищает текст от пробелов, переводов коретки, заголовков и т.п.
    protected function cleanText($value)
    {
	return trim(preg_replace('/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i', '', $value));
    }
    
    //очищает скрипт в html
    protected function cleanScript($value){
        return trim(preg_replace('#<script[^>]*>.*?</script>#is', '', $value));
    }
    
    //проверяет e-mail
    protected function ValidateAddress($address) {
    if (function_exists('filter_var')) { // PHP 5.2
        if(filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
          return false;
        } else {
          return true;
        }
      } else {
        return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
      }
    }

    
    //устанавливает отправителя
    public function setSender($sender){ 
        $this->sender = htmlspecialchars(strtolower($this->cleanLine($sender)));
    }
    
    //Добавляем адресат в копию
    public function addCC($cc){
        if(is_array($cc)){ //если задано массивом
            foreach($cc as $to){
                $to = $this->cleanLine($to); //очищаем лишнее
                $this->toCC[] = $to;
            }
        }else{
            $to = $this->cleanLine($cc);
            $this->toCC[] = $to;
        }
    }
    
    //Добавляем адресат в скрытую копию
    public function addBCC($bcc){
        if(is_array($bcc)){ //если задано массивом
            foreach($bcc as $to){
                $to = $this->cleanLine($to); //очищаем лишнее
                $this->toBCC[] = $to;
            }
        }else{
            $to = $this->cleanLine($bcc);
            $this->toBCC[] = $to;
        }
    }
    
    public function setSubject($subject){
        $this->subject = htmlspecialchars($this->cleanLine($subject));
    }
    
    public function setBody($content, $html='false'){
        if($html===true)
        {
            $this->isHTML = true;
            $this->content = $this->cleanScript($this->cleanText($content));
        }else{
            $this->content = htmlspecialchars($this->cleanText($content));
        }
    }
}
?>
