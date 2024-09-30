<div class="d-flex justify-content-end my-3">
    <i class="ri-add-circle-line ri-xl text-success " role="button" title="Tambah" onclick="addDataTugas()"></i>
</div>
<div class="table-responsive">
    <?php $header = ['Tugas', 'Aksi']; ?>
    <?php echo table('tugas', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>