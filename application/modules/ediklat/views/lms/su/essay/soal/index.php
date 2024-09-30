<div class="d-flex justify-content-end my-3">
    <i class="ri-add-circle-line ri-xl text-success " role="button" title="Tambah" onclick="addDataSoal()"></i>
</div>
<div class="table-responsive">
    <?php $header = ['Soal', 'Aksi']; ?>
    <?php echo table('soal', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>