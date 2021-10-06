<?php

function is_logged_in()
{
    //untuk memanggil perintah ci di helper php dengan instance 
    $ci = get_instance();
    if(!$ci->session->userdata('email')){
        redirect('auth');
    }else{
        $role_id = $ci->session->userdata('role_id');
        $menu = $ci->uri->segment(1);

        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];


        $userAccess = $ci->db->get_where('user_access_menu', [
                                        'role_id' =>$role_id, 
                                        'menu_id' => $menu_id

                                        ]);

        
                                        if($userAccess->num_rows() < 1) {
                                            redirect('auth/blocked');
                                        }
    }
}

function check_riski($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
   $rizsky = $ci->db->get('user_access_menu');

    //cara lain
   // $ci->db->get_where('user_access_menu',[
        //'role_id' => $role_id,
       //'menu_id' => $menu_id
      //  ]);

    //jika baris lebih besar dari 0 maka ceklis
    if($rizsky->num_rows()>0){
        return "checked='checked'";
    }
}