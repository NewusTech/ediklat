<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="table-responsive">
            <?php $header = ((count(array_intersect($userPermission, ['RMEMBER'])) > 0) ? ['Nama/Instansi','NIK' ,'NPSN','Kabupaten/Kota','Layanan Pendidikan','Status', 'Aksi'] : ['Nama/Instansi','NIK', 'NPSN','Kabupaten/Kota','Layanan Pendidikan','Status']); ?>
            <?php echo table('member', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>