<?php

?>
    <div id="admin-panel">
        <p>Админ панель</p>
        <div class="btn-toolbar-admin">
            <div class="btn-group">
                <a href="?preview" class="btn">Предварительный просмотр</a>
                <button class="btn" module="admin" action="newpage">Новая страница</button>
                <button class="btn" module="admin" action="editpage" idpage="<? echo $this->idpage; ?>">Редактировать страницу</button>
                <button class="btn" module="admin" action="copypage" idpage="<? echo $this->idpage; ?>">Клонировать страницу</button>
                
                
                <button class="btn" module="admin" action="listmodule">Список модулей</button>
            </div>
        </div>
    </div>
    <div id="admin-sub-panel">
        <span id="shText" class="btn btn-danger">Админ</span>
    </div>    