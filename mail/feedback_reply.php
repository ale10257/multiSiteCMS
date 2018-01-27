<?php
/** @var \app\core\feedback\FeedbackData $data */
?>
<h4>
    Имя отправителя: <?= $data->name ?>
</h4>
<p>
    Email: <?= $data->email ?>
</p>
<?php if (!empty($data->phone)) : ?>
    <p>
        Телефон: <?= $data->phone ?>
    </p>
<?php endif ?>
<h3>
    Текст сообщения:
</h3>
<p>
    <?= nl2br($data->text) ?>
</p>
