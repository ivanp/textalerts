<?php
//var_dump($user->memberships);exit;
?>
<h1>User Profile</h1>

<h3>Member of companies:</h3>
<table width="100%" border="1">
	<thead>
		<tr>
			<td>Name</td>
			<td>Status</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($user->memberships as $membership) {
			$company = $membership->company;
			?>
		<tr>
			<td><?php echo $company->name ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>


