<?php

/**
 * @file redact-price-item.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * 
 *Собираем блок с номерами страниц
 */
?>


<input type='text' onfocus='changeActEl(this)' class='inputPr' size=2 value='<?php print $price; ?>'><div class='butPr ui-state-default' onclick='clearPrice(this);'><span class='ui-icon ui-icon-trash'></span></div>