<?php
    $data = $this->getData();
?>
<form method="post">
<h2>Вход</h2>
<label for="login">Логин:</label>
<input type="text" id="login" name="login" value="<?php if ( isset($data['login']) ) echo $data['login'];?>" required />
<label for="password">Пароль:</label>
<input type="password" id="password" name="password" required />
<button type="submit">Войти</button>
<?php
    if ( isset($data['error']) )
        echo $data['error'];
?>
</form>