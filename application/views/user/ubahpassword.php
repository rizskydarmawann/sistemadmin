        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= $title;?></h1>
            <div class="row">
                <div class="col-lg-6">
                    <?= $this->session->flashdata('message');?>
                    <form action="<?= base_url('user/editpassword')?>" method="POST">
                        <div class="form-group">
                            <label for="password_saatini">Password Saat Ini</label>
                            <input type="password" class="form-control" id="password_saatini" name="password_saatini">
                            <?= form_error('password_saatini',' <small class="text-danger pl-3">','</small>');?>
                        </div>
                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password_baru">
                            <?= form_error('password_baru',' <small class="text-danger pl-3">','</small>');?>
                        </div>
                        <div class="form-group">
                            <label for="password_baru2">Ulangi Password</label>
                            <input type="password" class="form-control" id="password_baru2" name="password_baru2">
                            <?= form_error('password_baru2',' <small class="text-danger pl-3">','</small>');?>
                        </div>
                        <div class="from-group">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->