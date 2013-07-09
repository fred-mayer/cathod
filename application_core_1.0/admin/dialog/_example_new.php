
// Здесь ворма с полями для создания нового модуля
    <div class="control-group">
        <label class="control-label">Псевдоним:</label>
    	<div class="controls">
            <input type="text" id="alias" value="" />
        </div>
    </div>
    <textarea id="ckeditor" rows="10" cols="45" name="text"></textarea>

<script>
    var editor = CKEDITOR.replace( 'ckeditor' );
    
    // Эта функция обезательна
    // возвращает массив содержащий данные для отправки
    function getPost( title_module )
    {
        // title_module - имя модуля
        var alias = $('#alias').val();
        var content = editor.getData();
        return {'title_module':title_module, 'alias':alias, 'content':content};
    }
</script>