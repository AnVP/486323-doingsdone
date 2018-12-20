<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input <?= !empty($errors['name']) ? "form__input--error" : ""?>" type="text" name="name" id="name" value="<?=!empty($task['name']) ? esc($task['name']) : ""?>" placeholder="Введите название">

    <p class="form__message"><?= !empty($errors['name']) ? $errors['name'] : ""?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект <sup>*</sup></label>

    <select class="form__input form__input--select <?= !empty($errors['project']) ? "form__input--error" : ""?>" name="project" id="project">
    <?php foreach ($projects as $key => $value): ?>
      <option value="<?=$value['project_id'];?>" <?=(!empty($task['project']) and $task['project'] === $value['project_id']) ? "selected" : "";?>><?=esc($value['name']);?></option>
    <?php endforeach; ?>
    </select>

    <p class="form__message"><?= !empty($errors['project']) ? $errors['project'] : ""?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?= !empty($errors['date']) ? "form__input--error" : ""?>" type="date" name="date" id="date" value="<?= !empty($task['date']) ? $task['date'] : ""?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

    <p class="form__message"><?= !empty($errors['date']) ? $errors['date'] : "";?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file">
      <input class="visually-hidden" type="file" name="preview" id="preview" value="">

      <label class="button button--transparent" for="preview">
        <span>Выберите файл</span>
      </label>
    </div>

  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">

    <?php if (!empty($errors)) : ?>
        <p class="form__message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
  </div>
</form>
