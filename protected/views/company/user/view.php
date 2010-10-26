<?php

$this->pageTitle=Yii::app()->name . ' - '.$user->getDisplayName();
$this->breadcrumbs=array(
	'Users' => array('user/index'),
	$user->getDisplayName()
);

?>
<h2><?php echo $user->getDisplayName(); ?></h2>

[ <a href="<?php echo $user->createCompanyEditUrl($company) ?>">Edit this user</a> ]
<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php endif; ?>

<br/><br/>

<table width="100%" border="1">
	<caption>Subscribed to:</caption>
	<thead>
		<tr>
			<th width="30%">Name</th>
			<th>Members</th>
			<th>Role</th>
			<th>Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($user->getSubscriptions($company) as $subscription) {
			$group = $subscription->group;
			?>
		<tr>
			<td><?php echo $group->title; ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="checkbox"/> E-mail
					<input type="checkbox"/> Text
					<button>Unsubscribe</button></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<br/><br/>
<p>
	Subscribe to this group:<br/>
	<select>
		<option>Group 1</option>
		<option>Group 2</option>
	</select>
	<input type="checkbox" checked="checked"/> E-mail
	<input type="checkbox" checked="checked"/> Text
	<button>Subscribe</button>
</p>