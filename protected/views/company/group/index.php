<?php
$roles = array('Member', 'Administrator', 'Sender', 'Non-member');

?>
<h2>Groups</h2>
<?php if ($this->company->isAdministrator(User::getLoggedUser())): ?>
<h4><a href="<?php echo $this->createUrl('group/create'); ?>">Create new group</a></h4>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('group')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('group'); ?>
    </div>
<?php endif; ?>

<?php echo CHtml::beginForm(); ?>
<table width="100%" border="1">
	<thead>
		<tr>
			<th width="50%">Name</th>
			<th width="10%">Members</th>
			<th>Your Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($groups as $group): ?>
		<tr>
			<td>
				<?php if ($this->company->isAdministrator(User::getLoggedUser())): ?>
				<a href="<?php echo $group->createViewUrl()?>"><?php echo htmlentities($group->title)?></a>
				<?php else: ?>
				<?php echo htmlentities($group->title)?>
				<?php endif; ?>
			</td>
			<td class="center"><?php echo $group->subscriberCount ?></td>
			<td>
				<?php if (!Yii::app()->user->isGuest) {
					$user = Yii::app()->user->record;
					$this->widget('application.components.widgets.GroupSubscriptionCheckboxes', array('group'=>$group,'user'=>$user));
				}
				?>

				<?php /*
				echo <?php $this->widget('path.to.WidgetClass'); ?>
				$row++;
				$sm_name = "subscription_mail[$row]";
				$sm_id = "subscription_mail_$row";
				echo CHtml::checkBox($sm_name, false, array('id'=>$sm_id));
				echo CHtml::label('E-mail', $sm_id);
				$st_name = "subscription_text[$row]";
				$st_id = "subscription_text_$row";
				echo CHtml::checkBox($st_name, false, array('id'=>$st_id));
				echo CHtml::label('Text', $st_id);
				*/ ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?php if ($pages->pageCount > 1): ?>
<div class="pagelinks">
<?$this->widget('CLinkPager', array(
    'pages' => $pages,
))?>
</div>
<?php endif; ?>
