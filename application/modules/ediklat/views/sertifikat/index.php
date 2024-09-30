<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="d-flex justify-content-end my-3">
            <?php echo ((in_array('CCERTIFICATE', $userPermission)) ? '<i class="ri-add-circle-line ri-xl text-success " role="button" title="Tambah" onclick="addData()"></i>' : '') ?>
        </div>
        <div class="table-responsive">
            <?php $header = ((count(array_intersect($userPermission, ['UCERTIFICATE', 'DCERTIFICATE'])) > 0) ? ['Nama', 'No Sertifikat','Status', 'Aksi'] : ['Nama', 'No Sertifikat','Status']); ?>
            <?php echo table('sertifikat', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>