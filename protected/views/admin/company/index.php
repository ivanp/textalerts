<?php
$this->pageTitle=Yii::app()->name . ' - Companies';
$this->breadcrumbs=array(
	'Companies',
);
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<p>
<a href="<?php echo $this->createUrl('company/create')?>">Create new company</a>
</p>
<?php if(Yii::app()->user->hasFlash('company')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('company'); ?>
    </div>
<?php endif; ?>
<table width="100%">
	<thead>
		<tr>
			<th>Name</th>
			<th>Hostname</th>
			<th>Created</th>
			<th>Owner</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($companies as $company) {

		?>
		<tr>
			<td><?php echo htmlspecialchars($company->name)?></td>
			<td><a href="<?php echo $company->createUrl('site/index')?>"><?php echo $company->host.'.'.Yii::app()->params['domain'] ?></a></td>
			<td><?php echo $company->created ?></td>
			<td><?php echo $company->owner->email ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>