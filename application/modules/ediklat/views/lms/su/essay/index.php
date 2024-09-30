<div class="d-flex justify-content-end align-items-center bg-white rounded py-1 px-1">
    <span class="badge bg-success" role="button" onclick="addEssay()"><i class="fa fa-plus"></i> Tambah</span>
</div>
<div class="table-responsive">
    <?php $header = ((count(array_intersect($userPermission, ['RESSAY'])) > 0) ? ['Judul', 'Aksi'] : ['Judul']); ?>
    <?php echo table('essay', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>