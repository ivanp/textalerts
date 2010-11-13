<tr>
	<td><a href="<?php echo $this->createUrl('user/profile',
		array('id'=>$user->id))?>"><?php echo $user->getDisplayName() ?></a></td>
	<td><?php echo $user->email ?></td>
	<td><?php if ($this->company->isAdministrator(User::getLoggedUser())) {
			$this->widget('application.components.widgets.GroupSubscriptionCheckboxes',
				array('group'=>$group,'user'=>$user));
		}
		?></td>
</tr>