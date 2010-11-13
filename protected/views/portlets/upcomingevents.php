<?php

$occurs = Occurrence::modelByCompany($this->company)->upcoming()->findAll();
?>
<?php if (count($occurs)): ?>
<ul>
	<?php foreach ($occurs as $occur): ?>
	<li><?php echo $occur->event->subject ?> (<?php echo $occur->startdate?>)</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
No up coming events
<?php endif; ?>