<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
       

        

        $data['title'] ='My Profile';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('tamplates/header', $data);
        $this->load->view('tamplates/sidebar', $data);
        $this->load->view('tamplates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('tamplates/footer');
    }

    public function edit_profile()
    {

        $data['title'] ='Edit Profile';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if($this->form_validation->run() == false){
           
            $this->load->view('tamplates/header', $data);
            $this->load->view('tamplates/sidebar', $data);
            $this->load->view('tamplates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('tamplates/footer');

        }else{
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            
            //CEK JIKA ADA GAMBAR YANG AKAN DIUPLOAD
            $upload_image = $_FILES['image']['name'];

            if($upload_image){
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/profile/';
                
                $this->load->library('upload', $config);

                if($this->upload->do_upload('image')){

                    //untuk meukar gambar yang baru upload
                    $gambar_lama = $data ['user']['image'];
                    if($gambar_lama != 'default.jpg'){
                        unlink(FCPATH . 'assets/img/profile/' . $gambar_lama);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                }else{
                    echo $this->upload->display_errors();
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Profil Berhasil Berubah !</div>');
                redirect('user');
        }
   
    }

    public function editpassword()
    {
        $data['title'] ='Edit Password';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        //untuk password saat ini
        $this->form_validation->set_rules('password_saatini', 'Password Saat Ini', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[3]|matches[password_baru2]');
        $this->form_validation->set_rules('password_baru2', 'Komfirmasi Password', 'required|trim|min_length[3]|matches[password_baru]');

        if($this->form_validation->run() == false){
            $this->load->view('tamplates/header', $data);
            $this->load->view('tamplates/sidebar', $data);
            $this->load->view('tamplates/topbar', $data);
            $this->load->view('user/ubahpassword', $data);
            $this->load->view('tamplates/footer');
        }else{
            //tangkap password saat ini
            $password_saatini = $this->input->post('password_saatini');
            $password_baru = $this->input->post('password_baru');

            //cek
            if(!password_verify($password_saatini, $data['user']['password'])){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Password Saat Ini Salah !</div>');
                    redirect('user/editpassword');
            } else{
                //kalo passwoerd sama tidak bole
               if ($password_saatini == $password_baru) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Password Baru Tidak Boleh Sama Dengan Password Saat ini !</div>');
                redirect('user/editpassword');
               }else{
                   // password sudah ok
                   $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);


                    //set passwordnya dengan password baru yang sudah di hash
                    $this->db->set('password', $password_hash);

                   //ubah password

                   $this->db->where('email', $this->session->userdata('email'));
                   $this->db->update('user');
                   
                   $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                   Password Berhasil Berubah !</div>');
                   redirect('user/editpassword');
               }
            }

        }
   
    }
}