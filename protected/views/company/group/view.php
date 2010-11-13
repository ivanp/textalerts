<?php
//var_dump($this->uniqueid, $this->action->id);
$user=User::getLoggedUser();
$is_sender=$this->company->isSender($user);
$is_admin=$this->company->isAdministrator($user);
?>
<h2><?php echo $group->title?></h2>
<?php if (strlen($group->description)): ?>
<p><?php echo $group->description?></p>
<?php endif; ?>

<p>

<?php if ($is_admin): ?>
	<button onclick="location.href='<?php echo $this->createUrl('group/edit',array('id'=>$group->id))?>'">Change group info</button>
	<button onclick="location.href='<?php echo $this->createUrl('group/delete',array('id'=>$group->id))?>'">Delete group</button>
<?php endif; ?>
<?php if ($is_sender||$is_admin): ?>
	<button onclick="location.href='<?php echo $this->createUrl('message/create',array('group_id'=>$group->id))?>'">Create new message</button>
<?php endif; ?>

</p>

<h3>Users</h3>

<table width="100%" border="1">
	<thead>
		<tr>
			<th>Name</th>
			<th>E-mail</th>
			<th>Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($group->subscribers as $subscriber) {
			$user = $subscriber->user;
		?>
		<?php $this->renderPartial('_user_row', array('group'=>$group,'user'=>$user), false) ?>
		<?php } ?>
	</tbody>
</table>



<div class="clear"></div>


<form id="adduserform" action="<?php echo $this->createUrl('/group/addemail',array('id'=>$group->id)) ?>" method="post">
<div id="adduserbox" class="form">
	Add a user: <?php
	$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'id'=>'addusermail',
    'name'=>'email',
    'sourceUrl'=>$this->createUrl('/user/searchac'),
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'showAnim'=>'fold',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));
	?><input id="adduserbtn" type="submit" value="Add"/>
</div>
</form>