<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

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
        $data['title'] ='Menu Management';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array(); //untuk sebari array

        $data['menu'] = $this->db->get('user_menu')->result_array(); //banyak array

        $this->form_validation->set_rules('menu', 'Menu', 'required',[
            'required' => 'Data Harus Diisi !'
        ]);
        if($this->form_validation->run()==false){
            
            $this->load->view('tamplates/header', $data);
            $this->load->view('tamplates/sidebar', $data);
            $this->load->view('tamplates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('tamplates/footer');
        }else{
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu Berhasil Ditambahkan !</div>');
            redirect('menu');       
        }
    
    }


    //ini pakamodal
  

  //  public function hapus($id)
 //   {
   //     $where = array('id' => $id);
     //   $this->Model_menu->hapusDataMenu($where, 'user_menu');
       // $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
         //   Menu Berhasil Dihapus !</div>');
        //redirect('menu');
   // }

   public function hapus($id)
    {
        if($id==""){
            $this->session->set_flashdata('error',"Data Anda Gagal Di Hapus");
            redirect('menu');
        }else{
            $this->db->where('id', $id);
            $this->db->delete('user_menu');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu Berhasil Dihapus !</div>');
            redirect('menu');
        }
    }

    

    public function ubah()
    {
        
        $this->form_validation->set_rules('menu', 'Menu', 'required',[
            'required' => 'Data Harus Diisi !'
        ]);
        if($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('error','<div class="alert alert-success" role="alert">
            Data Gagal Diedit !</div>');
            redirect('menu');
        }else{
            $data=array(
                "menu"=>$_POST['menu'],
            );
            $this->db->where('id', $_POST['id']);
            $this->db->update('user_menu',$data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu Berhasil Diubah !</div>');
            redirect('menu');
        }
    }


    //dibawah ini pakai model
    

    public function submenu()
    {
        $data['title'] ='Submenu Management';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array(); 

        //ambil semua data//$data['subMenu'] = $this->db->get('user_sub_menu')->result_array();

        $this->load->model('Model_menu', 'menu'); //bikin nama singkat menu model
        $data['subMenu'] = $this->menu->getSubMenu(); // masukan sesudah this menu ini
        $data['menuuu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title','Title', 'required');
        $this->form_validation->set_rules('menu_id','Menu', 'required');
        $this->form_validation->set_rules('url','URL', 'required');
        $this->form_validation->set_rules('icon','Icon', 'required');



        if($this->form_validation->run() == false){
            
            $this->load->view('tamplates/header', $data);
            $this->load->view('tamplates/sidebar', $data);
            $this->load->view('tamplates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('tamplates/footer');
        }else{
            $data=[
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')     

            ];

            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Submenu Berhasil Ditambah !</div>');
            redirect('menu/submenu');
        }

        
    }


}