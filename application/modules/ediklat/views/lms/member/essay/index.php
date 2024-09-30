
<div class="table-responsive">
    <?php $header = ((count(array_intersect($userPermission, ['RESSAY'])) > 0) ? ['Judul','Status', 'Aksi'] : ['Judul','Status']); ?>
    <?php echo table('essay', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>