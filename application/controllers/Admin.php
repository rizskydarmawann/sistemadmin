<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()

    {
        parent::__construct();  
        //ini fungsi helper php
        is_logged_in();  
        //! tanda seru jika tidak ada
        //if(!$this->session->userdata('email')){
          //  redirect('auth');
        //}

        
    }

    public function index()
    {
        $dt = new DateTime();
        $data['title'] ='Dashboard';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('tamplates/header', $data);
        $this->load->view('tamplates/sidebar', $data);
        $this->load->view('tamplates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('tamplates/footer');
    }

    public function role()
    {
        $data['title'] ='Role';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['role'] =$this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required',[
            'required' => 'Data Harus Diisi !']);
        

            if($this->form_validation->run()==false){
            
                $this->load->view('tamplates/header', $data);
                $this->load->view('tamplates/sidebar', $data);
                $this->load->view('tamplates/topbar', $data);
                $this->load->view('admin/role', $data);
                $this->load->view('tamplates/footer');
            }else{
                $this->db->insert('user_role', ['role' => $this->input->post('role')]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                Role Berhasil Ditambahkan !</div>');
                redirect('admin/role');       
            }
     

    }

    public function hapus($id)
    {
        if($id==""){
            $this->session->set_flashdata('error',"Data Anda Gagal Di Hapus");
            redirect('admin/role');
        }else{
            $this->db->where('id', $id);
            $this->db->delete('user_role');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Role Berhasil Dihapus !</div>');
            redirect('admin/role');
        }
    }

    

    public function ubah()
    {
        
        $this->form_validation->set_rules('role', 'Role', 'required',[
            'required' => 'Data Harus Diisi !'
        ]);
        if($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('error','<div class="alert alert-success" role="alert">
            Data Gagal Diedit !</div>');
            redirect('admin/role');
        }else{
            $data=array(
                "role"=>$_POST['role'],
            );
            $this->db->where('id', $_POST['id']);
            $this->db->update('user_role',$data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Role Berhasil Diubah !</div>');
            redirect('admin/role');
        }
    }

    public function roleAccess($role_id)
    {
        $data['title'] ='Role Access';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['role'] =$this->db->get_where('user_role', ['id' => $role_id])->row_array();

        //untuk tidak menampilkan semua menu untuk akses menu
        $this->db->where('id !=', 11);
        $data['menu'] = $this->db->get('user_menu')->result_array();
        

            if($this->form_validation->run()==false){
            
                $this->load->view('tamplates/header', $data);
                $this->load->view('tamplates/sidebar', $data);
                $this->load->view('tamplates/topbar', $data);
                $this->load->view('admin/role-access', $data);
                $this->load->view('tamplates/footer');
            }else{
                $this->db->insert('user_role', ['role' => $this->input->post('role')]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                Role Berhasil Ditambahkan !</div>');
                redirect('admin/role');       
            }
     

    }

    public function ubahakses()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data =[
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $rizsky = $this->db->get_where('user_access_menu', $data);

        if($rizsky->num_rows() < 1){
            $this->db->insert('user_access_menu', $data);

        }else{
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akses Berhasil Berubah !</div>');

    }


}