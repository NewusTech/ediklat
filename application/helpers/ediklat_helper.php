<?php
function getOneValue(string $table = '', array $where = [], string $field = '')
{
    $CI = get_instance();
    $data = $CI->db->select($field)->get_where($table, $where)->row_array();
    if ($data == NULL) {
        return '';
    } else {
        return isset($data[$field])?$data[$field]:'';
    }
}

function getNotif()
{
    $CI = get_instance();
    $member = $CI->db->get_where('member', [
        'deleteAt' => NULL,
        'userCode' => $CI->session->userdata('userCode'),
    ])->row_array();
    if($member == NULL){
       return [];
    }
    if ($member['verify'] == '0') {
        return [];
    }
    $getAllNotif = $CI->db->order_by('notifCode', 'DESC')->get_where('notif', ['type' => 'kegiatan', 'memberCode' => $member['memberCode']])->result_array();
    return $getAllNotif;
}


function getNotifTotal()
{
    $CI = get_instance();
    $member = $CI->db->get_where('member', [
        'deleteAt' => NULL,
        'userCode' => $CI->session->userdata('userCode'),
    ])->row_array();
    if($member == NULL){
       return 0;
    }
    if ($member['verify'] == '0') {
        return 0;
    }
    $getAllNotif = $CI->db->order_by('notifCode', 'DESC')->get_where('notif', ['type' => 'kegiatan', 'isRead' => '0', 'memberCode' => $member['memberCode']])->result_array();
    return count($getAllNotif);
}

function bidangPT($code = '')
{
    $data = [
        '0' => 'Pendidikan',
        '1' => 'Non Pendidikan'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

function penyelenggara($code = '')
{
    $data = [
        '0' => 'instistusi / internal',
        '1' => 'NGO / Lembaga Swasta',
        '2' => 'Universitas',
        '3' => 'Lembaga Internasional',
        '4' => 'Asosiasi Profesi / Komunitas',
        '5' => 'Pemerintah Kabupaten/Kota',
        '6' => 'Pemerintah Provinsi',
        '7' => 'Pemerintah Pusat',
        '8' => 'Lembaga Swasta'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

function kedudukan($code = '')
{
    $data = [
        '0' => 'Sekolah/Gugus/Kecamatan',
        '1' => 'Kota/Kabupaten',
        '2' => 'Provinsi',
        '3' => 'Nasional',
        '4' => 'Internasional'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

function posisi($code = '')
{
    $data = [
        '0' => 'Ketua',
        '1' => 'Pengurus Inti',
        '2' => 'Anggota'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}


function ruangLingkup($code = '')
{
    $data = [
        '0' => 'Sekolah',
        '1' => 'Komunitas Luar Sekolah',
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

function sasaran($code = '')
{
    $data = [
        '0' => 'Siswa',
        '1' => 'Guru',
        '2' => 'Masyarakat Umum'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

function status($code = '')
{
    $data = [
        '0' => '-',
        '1' => 'Tidak Lulus',
        '2' => 'Lulus'
    ];
    if ($code != '') {
        return $data[$code];
    } else {
        return $data;
    }
}

/** 
 * Write code on Method
 *
 * @return response()
 */
function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];
        
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
         
    for ($currentDate = $startDate; $currentDate <= $endDate; 
                                    $currentDate += (86400)) {
                                            
        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}
