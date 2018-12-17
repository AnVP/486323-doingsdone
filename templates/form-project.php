<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="" method="post">
  <div class="form__row">
    <label class="form__label" for="project_name">Название <sup>*</sup></label>

    <input class="form__input <?= !empty($errors['name']) ? "form__input--error" : ""?>" type="text" name="name" id="project_name" value="<?=!empty($project['name']) ? esc($project['name']) : ""?>" placeholder="Введите название проекта">
    <p class="form__message"><?= !empty($errors['name']) ? $errors['name'] : ""?></p>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">

    <?php if (!empty($errors)) : ?>
        <p class="form__message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
  </div>
</form>
