<?php
class Seed_permission extends CI_Controller
{
  function index()
  {
    if (stripos(PHP_OS, "WIN") === 0) {
      $permission = json_decode(file_get_contents(APPPATH . '\\controllers\permission.json'), true);
    } else {
      $permission = json_decode(file_get_contents(APPPATH . '/controllers/permission.json'), true);
    }
    foreach ($permission as $one) {
      $cm = $this->db->get_where('module', ['module' => $one['module']])->row();
      if ($cm) {
        $moduleCode = $cm->moduleCode;
      } else {
        $this->db->insert('module', ['module' => $one['module']]);
        $moduleCode = $this->db->insert_id();
      }
      foreach ($one['permission'] as $two) {
        $cp = $this->db->get_where('permission', ['permission' => $two['permission']])->row();
        if ($cp) {
          echo 'permission ' . $two['permission'] . ' is exist';
          echo '<br>';
        } else {
          $this->db->insert('permission', ['moduleCode' => $moduleCode, 'permission' => $two['permission'], 'description' => $two['description']]);
          $id = $this->db->insert_id();
          echo 'permission ' . $two['permission'] . ' success add with id ' . $id;
          echo '<br>';
        }
      }
    }
    echo '<br><br>';
    //set to su
    $per = $this->db->get_where('permission', ['deleteAt' => NULL])->result();
    foreach ($per as $r) {
      $cp = $this->db->get_where('role_permission', ['permissionCode' => $r->permissionCode])->row();
      if ($cp) {
        echo 'role permission ' . $r->permission . ' is exist in super admin';
        echo '<br>';
      } else {
        $this->db->insert('role_permission', ['permissionCode' => $r->permissionCode, 'roleCode' => 1]);
        $id = $this->db->insert_id();
        echo 'role permission '  . $r->permission . ' success add to super admin with id ' . $id;
        echo '<br>';
      }
    }
  }

   public function status()
   {
       $activity = $this->db->where_not_in('activityCode',['19','20','21'])->get_where('activity',['deleteAt' => NULL])->result_array();
       foreach($activity as $k => $v){
            $participant = $this->db->get_where('participant',['status' => '1','deleteAt' => NULL,'activityCode' => $v['activityCode']])->result_array();
            $betweenDate = getBetweenDates($v['startDate'],$v['endDate']);
            $temp = [];
            foreach($betweenDate as $t => $g){
                $temp[$g] = '1';
            }
            foreach($participant as $n => $r){
                $update = $this->db->where('participantCode',$r['participantCode'])->update('participant',[
                    'statusDetail' => json_encode($temp,TRUE)
                ]);
            }
       }
   }
   
   public function status_now()
   {
       $activity = $this->db->where_in('activityCode',['19','20','21'])->get_where('activity',['deleteAt' => NULL])->result_array();
       foreach($activity as $k => $v){
            $participant = $this->db->get_where('participant',['status' => '0','deleteAt' => NULL,'activityCode' => $v['activityCode']])->result_array();
            $betweenDate = getBetweenDates($v['startDate'],$v['endDate']);
            $temp = [];
            foreach($betweenDate as $t => $g){
                if($g <= date('Y-m-d')){
                    $temp[$g] = '1';
                }else{
                    $temp[$g] = '0';
                }
            }
            foreach($participant as $n => $r){
                $update = $this->db->where('participantCode',$r['participantCode'])->update('participant',[
                    'statusDetail' => json_encode($temp,TRUE)
                ]);
            }
       }
   }
   
    public function status_not_in()
   {
       $activity = $this->db->where_in('activityCode',['19','20','21'])->get_where('activity',['deleteAt' => NULL])->result_array();
       foreach($activity as $k => $v){
            $participant = $this->db->get_where('participant',['status' => '0','deleteAt' => NULL,'activityCode' => $v['activityCode']])->result_array();
            $betweenDate = getBetweenDates($v['startDate'],$v['endDate']);
            $temp = [];
            foreach($betweenDate as $t => $g){
                $temp[$g] = '0';
            }
            foreach($participant as $n => $r){
                $update = $this->db->where('participantCode',$r['participantCode'])->update('participant',[
                    'statusDetail' => json_encode($temp,TRUE)
                ]);
            }
       }
   }
}
