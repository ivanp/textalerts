<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$companies = $user->companies;
?>

<?php if (count($companies)): ?>
<h3>You are the owner of these companies:</h3>
<table width="100%" border="1">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($user->companies as $company) {
			?>
		<tr>
			<td><a href="<?php echo $company->createUrl('site/index'); ?>"><?php echo $company->name ?></a></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php endif; ?>
<br/><br/>
<h3>Member of companies:</h3>
<table width="100%" border="1">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($user->memberships)): ?>
			<?php foreach ($user->memberships as $membership) {
				$company = $membership->company;
				?>
			<tr>
				<td><a href="<?php echo $company->createUrl('site/index'); ?>"><?php echo $company->name ?></a></td>
				<td></td>
			</tr>
			<?php } ?>
		<?php else: ?>
			<tr>
				<td colspan="2">You are not subscribed to any company</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
