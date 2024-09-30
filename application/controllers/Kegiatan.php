<?php
require FCPATH . 'vendor/autoload.php';
require_once APPPATH . "libraries/phpqrcode/qrlib.php";


use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class Kegiatan extends CI_Controller
{
    // public function index()
    // {
    //     $data = $this->db->order_by('activityCode', 'DESC')->get_where('activity', ['deleteAt' => NULL, 'certificateCode !=' => NULL])->result_array();
    //     $result = [];
    //     foreach ($data as $k => $v) {
    //         $activity = $v;
    //         $participant = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'deleteAt' => NULL])->result_array();
    //         $activity['jumlahPeserta'] = count($participant);
    //         $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $v['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
    //         $activity['jumlahSertifikat'] = count($sertifikat);
    //         $result[] = $activity;
    //     }
    //     $data['activity'] = $result;
    //     $data['_view'] = 'kegiatan';
    //     $this->load->view('layouts/front/main', $data);
    // }

    public function detail($nameActivity = '')
    {
        if ($nameActivity == '') {
            redirect('kegiatan/index');
        }
        $nameActivity = urldecode($nameActivity);
        $activity = $this->db->get_where('activity', ['deleteAt' => NULL, 'name' => $nameActivity])->row_array();
        if ($activity == NULL) {
            redirect('kegiatan/index');
        }
        $participant = $this->db->select('participantCode,name,agency,stateCode,verify,status')->get_where('participant', ['activityCode' => $activity['activityCode'], 'deleteAt' => NULL])->result_array();
        $activity['peserta'] = $participant;
        $activity['jumlahPeserta'] = count($participant);
        $sertifikat = $this->db->select('participantCode')->get_where('participant', ['activityCode' => $activity['activityCode'], 'status' => '1', 'deleteAt' => NULL])->result_array();
        $activity['jumlahSertifikat'] = count($sertifikat);
        $data['activity'] = $activity;
        $data['_view'] = 'detail';
        $this->load->view('layouts/front/main', $data);
    }

    public function peserta()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
            die();
        }
        $params = [];
        $data['status'] = TRUE;
        $data['data'] = $this->load->view('peserta', $params, TRUE);
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function pesertaList(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/peserta_model', 'peserta');
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        if ($kegiatanCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
        } else {
            $result = $this->kegiatan->get_by_id($kegiatanCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
            } else {
                $list = $this->peserta->get_datatables($kegiatanCode);
                $data = array();
                foreach ($list as $v) {
                    $row = array();

                    $row[] = '
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <p class="text-xs fw-semibold d-flex py-auto my-auto">' . $v->name . '</p>
                                    <p class="text-xs d-flex py-auto my-auto">' . $v->agency . '</p>
                                </div>
                            </div>';
                    $row[] = '<p class="text-xs d-flex py-auto my-auto">' . ($v->status == '1' ? 'Hadir' : 'Tidak Hadir') . '</p>';

                    if ($result->type == 'general') {
                        $row[] = "
                            <div class='d-flex justify-content-center'>
                                " . ($v->status == '1' ? "<a href='" . base_url('kegiatan/downloadSertifikat/' . base64_encode($v->participantCode . '-' . $kegiatanCode)) . "' class='btn btn-sm btn-primary'><i class='ri-download-line' role='button' title='Download'></i></a>" : "") . "
                            </div>
                            ";
                    } else {
                        $row[] = "
                            <div class='d-flex justify-content-center'>
                                " . ($v->status == '1' && $v->verify == '1' ? "<a href='" . base_url('kegiatan/downloadSertifikat/' . base64_encode($v->participantCode . '-' . $kegiatanCode)) . "' class='btn btn-sm btn-primary'><i class='ri-download-line' role='button' title='Download'></i></a>" : "") . "
                            </div>
                            ";
                    }

                    $data[] = $row;
                }

                $output = array(
                    "draw" => @$_POST['draw'],
                    "recordsTotal" => $this->peserta->count_all($kegiatanCode),
                    "recordsFiltered" => $this->peserta->count_filtered($kegiatanCode),
                    "data" => $data,
                );
                //output to json format
                echo json_encode($output);
            }
        }
    }

    // public function materi()
    // {
    //     if (!$this->input->is_ajax_request()) {
    //         exit('No direct script access allowed');
    //         die();
    //     }
    //     $params = [];
    //     $data['status'] = TRUE;
    //     $data['data'] = $this->load->view('materi', $params, TRUE);
    //     return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    // }

    // public function materiList(string $kegiatanCode = '')
    // {
    //     $this->load->model('ediklat/kegiatan_model', 'kegiatan');
    //     $this->load->model('ediklat/materi_model', 'materi');

    //     if ($kegiatanCode == '') {
    //         $data = array(
    //             'status'         => FALSE,
    //             'message'         => "Activity code is required"
    //         );
    //     } else {
    //         $result = $this->kegiatan->get_by_id($kegiatanCode);
    //         if ($result == NULL) {
    //             $data = array(
    //                 'status'         => FALSE,
    //                 'message'         => "Kegiatan tidak ditemukan!"
    //             );
    //         } else {
    //             $list = $this->materi->get_datatables($kegiatanCode);
    //             $data = array();
    //             foreach ($list as $result) {
    //                 $row = array();

    //                 $row[] = '
    //                                 <p class="text-xs text-bold d-flex py-auto my-auto">' . $result->name . '</p>
    //                             ';
    //                 $row[] = '
    //                                 <p class="text-xs text-bold d-flex py-auto my-auto">' . $result->description . '</p>
    //                             ';

    //                 //add html for action
    //                 $row[] = '
    //                     <div class="d-flex justify-content-center">
    //                         <a href="' . base_url('kegiatan/downloadMateri/' . base64_encode($result->file)) . '" class="btn btn-sm btn-primary"><i class="ri-download-line" role="button" title="Download"></i></a>
    //                     </div>
    //                     ';

    //                 $data[] = $row;
    //             }

    //             $output = array(
    //                 "draw" => @$_POST['draw'],
    //                 "recordsTotal" => $this->materi->count_all($kegiatanCode),
    //                 "recordsFiltered" => $this->materi->count_filtered($kegiatanCode),
    //                 "data" => $data,
    //             );
    //             //output to json format
    //             echo json_encode($output);
    //         }
    //     }
    // }

    // public function absen(string $kegiatanCode = '')
    // {
    //     $this->load->model('ediklat/kegiatan_model', 'kegiatan');
    //     $this->load->model('ediklat/peserta_model', 'peserta');

    //     if ($kegiatanCode == '') {
    //         $data = array(
    //             'status'         => FALSE,
    //             'message'         => "Activity code is required"
    //         );
    //     } else {
    //         $result = $this->kegiatan->get_by_id($kegiatanCode);
    //         if ($result == NULL) {
    //             $data = array(
    //                 'status'         => FALSE,
    //                 'message'         => "Kegiatan tidak ditemukan!"
    //             );
    //             return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //         } else {
    //             $peserta = $this->db->get_where('participant', ['deleteAt' => NULL, 'activityCode' => $result->activityCode, 'nik' => $this->input->post('nik')])->row_array();
    //             if ($peserta == NULL) {
    //                 $data = array(
    //                     'status'         => FALSE,
    //                     'message'         => "NIK tidak ditemukan!"
    //                 );
    //                 return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //             }
    //             if ($peserta['status'] == '1') {
    //                 $data = array(
    //                     'status'         => FALSE,
    //                     'message'         => "Anda sudah melakukan pencatatan kehadiran sebelumnya"
    //                 );
    //                 return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //             }
    //             $params = [
    //                 'status' => 1
    //             ];
    //             $up = $this->db->where('participantCode', $peserta['participantCode'])->update('participant', $params);
    //             if ($up) {
    //                 $data['status'] = TRUE;
    //                 $data['message'] = "Berhasil melakukan pencatatan kehadiran";
    //             } else {
    //                 $data['status'] = FALSE;
    //                 $data['message'] = "Gagal melakukan pencatatan kehadiran";
    //             }
    //             return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //         }
    //     }
    // }

    // public function save(string $kegiatanCode = '')
    // {
    //     $this->load->model('ediklat/kegiatan_model', 'kegiatan');
    //     $this->load->model('ediklat/peserta_model', 'peserta');

    //     if ($kegiatanCode == '') {
    //         $data = array(
    //             'status'         => FALSE,
    //             'message'         => "Activity code is required"
    //         );
    //         return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //     } else {
    //         $result = $this->kegiatan->get_by_id($kegiatanCode);
    //         if ($result == NULL) {
    //             $data = array(
    //                 'status'         => FALSE,
    //                 'message'         => "Kegiatan tidak ditemukan!"
    //             );
    //             return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //         } else {
    //             $this->form_validation->set_error_delimiters('', '');
    //             $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
    //             $this->form_validation->set_rules('name', 'nama', 'trim|required');
    //             $this->form_validation->set_rules('phone', 'no handphone', 'trim|required');
    //             $this->form_validation->set_rules('address', 'alamat', 'trim|required');
    //             $this->form_validation->set_rules('npwp', 'NPWP', 'trim|required');
    //             $this->form_validation->set_rules('agency', 'instansi', 'trim|required');
    //             $this->form_validation->set_rules('rank_dinas', 'jabatan dalam dinas', 'required');
    //             $this->form_validation->set_rules('rank', 'pangkat/golongan', 'trim|required');
    //             $this->form_validation->set_rules('gender', 'jenis kelamin', 'trim|required');
    //             $this->form_validation->set_rules('education', 'pendidikan terakhir', 'trim|required');
    //             $this->form_validation->set_rules('birthplace', 'tempat/tanggal lahir', 'trim|required');
    //             $this->form_validation->set_rules('email', 'email', 'trim|required');

    //             if ($this->form_validation->run() == FALSE) {
    //                 $errors = array(
    //                     'name' => form_error('name'),
    //                     'nik' => form_error('nik'),
    //                     'phone' => form_error('phone'),
    //                     'address' => form_error('address'),
    //                     'npwp' => form_error('npwp'),
    //                     'agency' => form_error('agency'),
    //                     'rank' => form_error('rank'),
    //                     'rank_dinas' => form_error('rank_dinas'),
    //                     'gender' => form_error('gender'),
    //                     'education' => form_error('education'),
    //                     'birthplace' => form_error('birthplace'),
    //                     'email' => form_error('email'),
    //                 );
    //                 $data = array(
    //                     'status'         => FALSE,
    //                     'errors'         => $errors
    //                 );
    //                 return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //             } else {
    //                 $peserta = $this->db->get_where('participant', ['deleteAt' => NULL, 'activityCode' => $result->activityCode, 'nik' => $this->input->post('nik')])->row_array();
    //                 if ($peserta != NULL) {
    //                     $data = array(
    //                         'status'         => FALSE,
    //                         'message'         => "Anda telah mendaftar sebelumnya"
    //                     );
    //                     return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //                 }
    //                 $insert = array(
    //                     'name' => $this->input->post('name'),
    //                     'nik' => $this->input->post('nik'),
    //                     'phone' => $this->input->post('phone'),
    //                     'address' => $this->input->post('address'),
    //                     'npwp' => $this->input->post('npwp'),
    //                     'agency' => $this->input->post('agency'),
    //                     'rank' => $this->input->post('rank'),
    //                     'rank_dinas' => $this->input->post('rank_dinas'),
    //                     'gender' => $this->input->post('gender'),
    //                     'birthplace' => $this->input->post('birthplace'),
    //                     'email' => $this->input->post('email'),
    //                     'education' => $this->input->post('education'),
    //                     'activityCode' => $result->activityCode,
    //                     'status' => '0',
    //                 );
    //                 if (!isset($_FILES['picture'])) {
    //                     $data = array(
    //                         'status'         => FALSE,
    //                         'message'         => 'Foto harus diisi'
    //                     );
    //                     return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //                 }
    //                 $file_name = str_replace('.', '', md5(rand())) . '-' . uniqid();
    //                 $config['upload_path']          = FCPATH . '/assets/img/participant/';
    //                 $config['allowed_types']        = 'gif|jpg|jpeg|png';
    //                 $config['file_name']            = $file_name;
    //                 $config['overwrite']            = true;
    //                 $config['max_size']             = 10240;

    //                 $this->load->library('upload', $config);

    //                 if (!$this->upload->do_upload('picture')) {
    //                     // var_dump($this->upload->display_errors());
    //                     $data = array(
    //                         'status'         => FALSE,
    //                         'message'         => 'Foto gagal di upload'
    //                     );
    //                     return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //                 } else {
    //                     $uploaded_data = $this->upload->data();
    //                     $insert['picture'] = $uploaded_data['file_name'];
    //                 }
    //                 $insert = $this->peserta->save($insert);
    //                 if ($insert) {
    //                     $data['status'] = TRUE;
    //                     $data['message'] = "Berhasil mendaftar di kegiatan";
    //                 } else {
    //                     $data['status'] = FALSE;
    //                     $data['message'] = "Gagal mendaftar di kegiatan";
    //                 }
    //                 return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    //             }
    //         }
    //     }
    // }

    public function downloadMateri(string $namaFile = '')
    {
        force_download(path_by_os(FCPATH . 'assets/img/theory/' . base64_decode($namaFile)), NULL);
    }

    public function downloadSertifikat(string $data = '')
    {
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        $this->load->model('ediklat/peserta_model', 'peserta');
        $this->load->model('ediklat/sertifikat_model', 'sertifikat');
        $d = base64_decode($data);
        $data = explode('-', $d);
        $kegiatan = $this->db->get_where('activity',['activityCode' => $data[1]])->row();
        // var_dump($kegiatan);
        // die;
        $peserta = $this->peserta->get_by_id($data[0]);
        $sertifikat = $this->sertifikat->get_by_id($kegiatan->certificateCode);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file));

        $halaman = json_decode($sertifikat->position, TRUE);

        $this->session->set_userdata([
            'pdf' => [
                'path' => path_by_os(FCPATH . '/assets/img/certificate/' . $sertifikat->file),
                'totalHalaman' => $pageCount,
                'halaman' => $halaman
            ]
        ]);

        $dataPDF = $this->session->userdata('pdf');
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($dataPDF['path']);
        foreach ($dataPDF['halaman'] as $k => $v) {
            $template   = $pdf->importPage($k);
            $size       = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
            $pdf->useTemplate($template);

            if ($v['aktif'] == true) {

                $nomerLeft = $v['nomer']['left'];
                $nomerTop = $v['nomer']['top'];
                $nomerExample = $sertifikat->number;

                $namaX = $v['nama']['x'];
                $namaY = $v['nama']['y'];
                $namaWidth = $v['nama']['width'];
                $namaHeight = $v['nama']['height'];
                $namaExample = $peserta->name;

                $kegiatanX = $v['kegiatan']['x'];
                $kegiatanY = $v['kegiatan']['y'];
                $kegiatanWidth = $v['kegiatan']['width'];
                $kegiatanHeight = $v['kegiatan']['height'];
                $kegiatanExample = $kegiatan->name;

                $tanggalKegiatanLeft = $v['tanggalKegiatan']['left'];
                $tanggalKegiatanTop = $v['tanggalKegiatan']['top'];
                $tanggalKegiatanExample = tanggal_indo($kegiatan->startDate);
                $tanggal = date('Y-m-d');
                $day = date('D', strtotime($tanggal));
                $dayList = array(
                    'Sun' => 'Minggu',
                    'Mon' => 'Senin',
                    'Tue' => 'Selasa',
                    'Wed' => 'Rabu',
                    'Thu' => 'Kamis',
                    'Fri' => 'Jumat',
                    'Sat' => 'Sabtu'
                );
                $tanggalTandaTanganLeft = $v['tanggalTandaTangan']['left'];
                $tanggalTandaTanganTop = $v['tanggalTandaTangan']['top'];
                $tanggalTandaTanganExample = $dayList[$day] . ',' . tanggal_indo($tanggal);

                // Set font
                $pdf->SetFont('courier', 'B', 26);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->setXY($namaX, $namaY);
                $pdf->Cell($namaWidth, $namaHeight, $namaExample, 0, 1, 'C', false);

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->setXY($kegiatanX, $kegiatanY);
                $pdf->MultiCell($kegiatanWidth, $kegiatanHeight, $kegiatanExample, 0, 'C', false);

                $pdf->SetFont("helvetica", "B", 14);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($nomerLeft, $nomerTop, $nomerExample);

                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($tanggalKegiatanLeft, $tanggalKegiatanTop, $tanggalKegiatanExample);

                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($tanggalTandaTanganLeft, $tanggalTandaTanganTop, $tanggalTandaTanganExample);
                $textQRCode = $peserta->participantCode . '-' . $kegiatan->activityCode . '-' . substr($kegiatan->createAt, 0, 4);
                QRcode::png($textQRCode, path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), 'L', 5, 2);
                $pdf->Image(path_by_os(FCPATH . '/assets/img/qrcode/' . $textQRCode . '.png'), $v['QRCode']['x'], $v['QRCode']['y'], 25);
                
                $pdf->SetFont("helvetica", "B", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Text($v['textQRCode']['left'], $v['textQRCode']['top'], $textQRCode);
            }
        }
        $pdf->Output('D', "sertifkat.pdf", true);
    }

    public function imageHTML(string $kegiatanCode = '')
    {
        $this->load->model('ediklat/kegiatan_model', 'kegiatan');
        $data['status'] = TRUE;
        if ($kegiatanCode == '') {
            $data = array(
                'status'         => FALSE,
                'message'         => "Activity code is required"
            );
        } else {
            $result = $this->kegiatan->get_by_id($kegiatanCode);
            if ($result == NULL) {
                $data = array(
                    'status'         => FALSE,
                    'message'         => "Kegiatan tidak ditemukan!"
                );
            } else {
                $params = [
                    'title' => $result->name,
                    'image' => $result->image
                ];
                $data['data'] = $this->load->view('ediklat/kegiatan/image', $params, TRUE);
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
