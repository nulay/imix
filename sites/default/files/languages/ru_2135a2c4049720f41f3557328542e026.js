Drupal.locale = { 'pluralFormula': function($n) { return Number((((($n%10)==1)&&(($n%100)!=11))?(0):((((($n%10)>=2)&&(($n%10)<=4))&&((($n%100)<10)||(($n%100)>=20)))?(1):2))); }, 'strings': { "Unspecified error": "Неопределенная ошибка", "Select all rows in this table": "Отметить все строки таблицы", "Deselect all rows in this table": "Снять отметку со всех колонок таблицы", "Join summary": "Объединить анонс", "Split summary at cursor": "Отделить анонс от основного материала", "Changes made in this table will not be saved until the form is submitted.": "Сделанные в списке изменения не вступят в силу, пока вы не сохраните их.", "The changes to these blocks will not be saved until the \x3cem\x3eSave blocks\x3c/em\x3e button is clicked.": "Изменения, сделанные в блоках не вступят в силу пока вы не нажмете кнопку \x3cem\x3eСохранить блоки\x3c/em\x3e.", "Drag to re-order": "Переместите для изменения порядка" } };