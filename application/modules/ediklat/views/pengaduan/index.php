<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="table-responsive">
            <?php $header = ((count(array_intersect($userPermission, ['RPENGADUAN'])) > 0) ? ['Nama/Instansi', 'Pesan'] : ['Nama/Instansi', 'Pesan']); ?>
            <?php echo table('pengaduan', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>