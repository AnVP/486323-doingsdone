<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <?php $classname_error = isset($errors['name']) ? "form__input--error" : "";
    $value_error = isset($task['name']) ? $task['name'] : "";
    $error_text = isset($errors['name']) ? $errors['name'] : "" ?>

    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input  <?=$classname_error;?>" type="text" name="name" id="name" value="<?=$value_error;?>" placeholder="Введите название">

    <p class="form__message"><?=$error_text;?></p>
  </div>

  <div class="form__row">
    <?php $classname_error = isset($errors['project']) ? "form__input--error" : "";
    $error_text = isset($errors['project']) ? $errors['project'] : "" ?>

    <label class="form__label" for="project">Проект <sup>*</sup></label>

    <select class="form__input form__input--select  <?=$classname_error;?>" name="project" id="project">
    <?php foreach ($projects as $key => $value): ?>
    <?php $value_error = isset($task['project']) ? $task['project'] : $value['name']; ?>
      <option value="<?=$value_error;?>"><?=$value_error;?></option>
    <?php endforeach; ?>
    </select>

    <p class="form__message"><?=$error_text;?></p>
  </div>

  <div class="form__row">
    <?php $classname_error = isset($errors['date']) ? "form__input--error" : "";
    $value_error = isset($task['date']) ? $task['date'] : "";
    $error_text = isset($errors['date']) ? $errors['date'] : "" ?>

    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date  <?=$classname_error;?>" type="date" name="date" id="date" value="<?=$value_error;?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

    <p class="form__message"><?=$error_text;?></p>
  </div>

  <div class="form__row">
    <?php $classname_error = isset($errors['preview']) ? "form__input--error" : "";
    $value_error = isset($task['preview']) ? $task['preview'] : "";
    $error_text = isset($errors['preview']) ? $errors['preview'] : "" ?>

    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file  <?=$classname_error;?>">
      <input class="visually-hidden" type="file" name="preview" id="preview" value="<?=$value_error;?>">

      <label class="button button--transparent" for="preview">
        <span>Выберите файл</span>
      </label>
    </div>
    <p class="form__message"><?=$error_text;?></p>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">

    <?php if (isset($errors)) : ?>
        <p class="form__message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
  </div>
</form>
