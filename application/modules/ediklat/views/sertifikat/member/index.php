<div class="card mb-4">
    <div class="card-body px-5 pt-3 pb-3">
        <div class="table-responsive">
            <?php $header = ['Kegiatan', 'Kode Unik Sertifikat', 'Aksi']; ?>
            <?php echo table('sertifikat', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body px-5 pt-3 pb-3">
        <div class="table-responsive">
            <?php $header = ['Essay', 'Kode Unik Sertifikat', 'Aksi']; ?>
            <?php echo table('essay', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>