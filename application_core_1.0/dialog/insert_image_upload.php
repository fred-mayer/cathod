
<input type="file" id="img_file" name="img_file" />

<script>
    document.getElementById( "img_file" ).addEventListener("change", function(e){

        var parent = this.parentNode;
        
        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', '/ajax/admin/insert_image?action=upload', true );
        xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');
        
        var formData = new FormData();
        formData.append('img_file', e.target.files[0]);
        
        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 ) 
                    
                        var w = getWin();
			var tinymce = w.tinymce;
		
			tinymce.EditorManager.activeEditor.insertContent('<img src="' + this.response +'">');
			//tinymce.EditorManager.activeEditor.windowManager.close();
                
                    parent.innerHTML =this.response;
            }
        };
        
        xhr.upload.addEventListener( 'progress', function(e){

            if ( e.lengthComputable )
            {
                progress.value = e.loaded;
                progress.max = e.total;
                progress.innerHTML = Math.round( e.loaded / e.total * 100 ) + '%';
            }
        });
        
        
        xhr.send(formData);
        
            
        parent.innerHTML = '<progress></progress>';
        var progress = parent.getElementsByTagName('progress')[0];
        
    }, false);
    
    
    function getWin() {
		return (!window.frameElement && window.dialogArguments) || opener || parent || top;
	};
       
</script>