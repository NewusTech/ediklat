<?php
function getKodeBayar()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('kdtp_penjualan', 'desc')->get_where('tbl_transaksi_penjualan_pembayaran', ['publish' => 'T'])->row();
  $char = "B-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtp_penjualan, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getKodeBayarPembelian()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('kdtp_pembelian', 'desc')->get_where('tbl_transaksi_pembelian_pembayaran', ['publish' => 'T'])->row();
  $char = "PEM-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtp_pembelian, 4, 8);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getKodeKirim()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('kdtpdo_penjualan', 'desc')->get_where('tbl_transaksi_penjualan_do', ['publish' => 'T'])->row();
  $char = "K-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtpdo_penjualan, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getNoFaktur()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('idt_penjualan', 'desc')->get('tbl_transaksi_penjualan')->row();
  $char = "F-";
  if ($pen) {
    $noUrut = (int) substr($pen->nofaktur, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getKDTPR()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('idtr_penjualan', 'desc')->get_where('tbl_transaksi_penjualan_retur', ['publish' => 'T'])->row();
  $char = "R-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtpr, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getNoFakturPembelian()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('idt_pembelian', 'desc')->get('tbl_transaksi_pembelian')->row();
  $char = "F-";
  if ($pen) {
    $noUrut = (int) substr($pen->nofaktur, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function getKDTPRPembelian()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('idtr_pembelian', 'desc')->get_where('tbl_transaksi_pembelian_retur', ['publish' => 'T'])->row();
  $char = "R-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtpr, 2, 6);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
function get_stock($idbarang)
{
  $CI = &get_instance();
  $bar =  $CI->db->get_where('tbl_barang', ['publish' => 'T', 'idbarang' => $idbarang])->row();
  if ($bar) {
    return $bar->stok;
  }
  return null;
}
function get_qty_penjualan_detail($idtd_penjualan)
{
  $CI = &get_instance();
  $bar =  $CI->db->get_where('tbl_transaksi_penjualan_detail', ['publish' => 'T', 'idtd_penjualan' => $idtd_penjualan])->row();
  if ($bar) {
    return $bar->qty;
  }
  return null;
}
function get_harga_jual_penjualan_detail($idtd_penjualan)
{
  $CI = &get_instance();
  $bar =  $CI->db->get_where('tbl_transaksi_penjualan_detail', ['publish' => 'T', 'idtd_penjualan' => $idtd_penjualan])->row();
  if ($bar) {
    return $bar->harga_jual;
  }
  return null;
}
function get_qty_pembelian_detail($idtd_pembelian)
{
  $CI = &get_instance();
  $bar =  $CI->db->get_where('tbl_transaksi_pembelian_detail', ['publish' => 'T', 'idtd_pembelian' => $idtd_pembelian])->row();
  if ($bar) {
    return $bar->qty;
  }
  return null;
}
function get_harga_jual_pembelian_detail($idtd_pembelian)
{
  $CI = &get_instance();
  $bar =  $CI->db->get_where('tbl_transaksi_pembelian_detail', ['publish' => 'T', 'idtd_pembelian' => $idtd_pembelian])->row();
  if ($bar) {
    return $bar->harga_beli;
  }
  return null;
}

function getListTransaksi($jenis = 'semua', $status = null, $idpelanggan = null, $nofaktur = null, $tanggalStart = null, $tanggalEnd = null)
{
  $CI = &get_instance();
  $query = $CI->db
    ->order_by('tbl_transaksi_penjualan.rec_insert', 'desc')
    ->join('tbl_pelanggan', 'tbl_pelanggan.idpelanggan = tbl_transaksi_penjualan.idpelanggan');

  if ($jenis !== 'semua') {
    $query->where('jenis_do', strtoupper($jenis));
  }
  if ($status) {
    $query->where('status', $status);
  }
  if ($nofaktur) {
    $query->where('nofaktur', $nofaktur);
  }
  if ($idpelanggan) {
    $query->where('tbl_transaksi_penjualan.idpelanggan', $idpelanggan);
  }
  if ($tanggalStart && $tanggalEnd) {
    $tanggalStart = date("Y-m-d", strtotime($tanggalStart));
    $tanggalEnd = date("Y-m-d", strtotime($tanggalEnd));
    $query->where('tgl_transaksi >=', $tanggalStart);
    $query->where('tgl_transaksi <=',  $tanggalEnd);
  }
  return $query->get_where('tbl_transaksi_penjualan', ['tbl_transaksi_penjualan.publish' => 'T'])->result();
}

function getDetailTransaksi($id)
{
  $CI = &get_instance();
  $penjualan = $CI->db->order_by('tbl_transaksi_penjualan.rec_insert', 'desc')->join('tbl_pelanggan', 'tbl_pelanggan.idpelanggan = tbl_transaksi_penjualan.idpelanggan')->get_where('tbl_transaksi_penjualan', ['tbl_transaksi_penjualan.publish' => 'T', 'tbl_transaksi_penjualan.idt_penjualan' => $id])->row();

  $p_detail = $CI->db
    ->select('*,tbl_transaksi_penjualan_detail.harga_jual as harga_jual,tbl_transaksi_penjualan_detail.tonase as tonase')
    ->join('tbl_barang', 'tbl_barang.idbarang = tbl_transaksi_penjualan_detail.idbarang')
    ->join('tbl_barang_satuan', 'tbl_barang.idsatuan = tbl_barang_satuan.idsatuan')
    ->join('tbl_barang_kategori', 'tbl_barang.idkategori = tbl_barang_kategori.idkategori')
    ->get_where('tbl_transaksi_penjualan_detail', ['idt_penjualan' => $penjualan->idt_penjualan])
    ->result();

  $p_bayar = $CI->db
    ->select('*,tbl_transaksi_penjualan_pembayaran.total as total')
    ->join('tbl_transaksi_penjualan', 'tbl_transaksi_penjualan.idt_penjualan = tbl_transaksi_penjualan_pembayaran.idt_penjualan')
    ->join('tbl_metode_bayar', 'tbl_transaksi_penjualan_pembayaran.idmetode_bayar = tbl_metode_bayar.idmetode_bayar')
    ->join('tbl_status_bayar', 'tbl_transaksi_penjualan_pembayaran.idstatus_bayar = tbl_status_bayar.idstatus_bayar')
    ->join('tbl_rekening_bank', 'tbl_transaksi_penjualan_pembayaran.idrekeningbank = tbl_rekening_bank.idrekeningbank')
    ->order_by('tbl_transaksi_penjualan_pembayaran.tgl_pembayaran', 'asc')
    ->get_where('tbl_transaksi_penjualan_pembayaran', ['tbl_transaksi_penjualan_pembayaran.idt_penjualan' => $penjualan->idt_penjualan, 'tbl_transaksi_penjualan_pembayaran.publish' => 'T',])
    ->result();

  return [
    'transaksi' => $penjualan,
    'pembayaran' => $p_bayar,
    'detail' => $p_detail,
  ];
}
function checkExistPenjualan($idt_penjualan)
{
  $CI = &get_instance();
  return $CI->db->get_where('tbl_transaksi_penjualan', ['publish' => 'T', 'idt_penjualan' => $idt_penjualan])->row();
}
function checkExistPembelian($idt_pembelian)
{
  $CI = &get_instance();
  return $CI->db->get_where('tbl_transaksi_pembelian', ['publish' => 'T', 'idt_pembelian' => $idt_pembelian])->row();
}

function currencyToInt($input)
{
  return $input ? (int) str_replace(['Rp. ', '.'], '', $input) : 0;
}
function intToCurrency($input = 0)
{
  return 'Rp ' . number_format($input, 2, ',', '.');
}

function updateStatusTransaksi($idt_penjualan, $status)
{
  $CI = &get_instance();
  $CI->db->where('idt_penjualan', $idt_penjualan)->update('tbl_transaksi_penjualan', ['status' => $status]);
  return  $CI->db->affected_rows();
}

function getKDTPEMBELIANDO()
{
  $CI = &get_instance();
  $pen  = $CI->db->order_by('idtpdo_pembelian', 'desc')->get_where('tbl_transaksi_pembelian_do', [])->row();
  $char = "DOP-";
  if ($pen) {
    $noUrut = (int) substr($pen->kdtpdo_pembelian, 4, 8);
    $noUrut++;
    $newID = $char . sprintf("%06s", $noUrut);
  } else {
    $newID = $char . sprintf("%06s", 1);
  }
  return $newID;
}
