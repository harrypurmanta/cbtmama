<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Selamat Datang</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url() ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <?= $this->include('front/navbar') ?>
        </header>

        <div class="content-wrapper">
            <div class="container">
                <section class="content">
                    <div class="row">
                    <div class="col-md-12" style="display: flex;justify-content: center;margin-bottom:20px;">
                                <h2><b>MATERI</b></h2>
                            </div>
                        <div class="col-md-12">
                            <?php
                                $this->session = \Config\Services::session();
                                $user_id = $this->session->user_id;
                                $db = db_connect();
                                foreach ($materi as $key) {
                                    if ($key->materi_id == 1) {
                                        $group_id = 1;
                                    } else {
                                        $group_id = 2;
                                    }

                                    $query = $db->query("SELECT * FROM respon WHERE materi = $key->materi_id AND created_user_id = $user_id AND status_cd = 'normal'")->getResultArray();
                                    if (count($query)>0) {
                                        $click = base_url()."/materi/hasiltryout/".$key->materi_id;
                                        $class_bg = "bg-green";
                                        $a_bg = "bg-green";
                                        $icon= "fa-check";
                                        $text= "Selesai";
                                    } else {
                                        $click = "onclick='showtoken(".$group_id.",".$key->materi_id.")'";
                                        $class_bg = "bg-gray";
                                        $a_bg = "bg-blue";
                                        $icon= "fa-arrow-circle-right";
                                        $text= "Mulai";
                                    }
                            ?>
                            <div class="col-lg-3" style="border-radius:10px;">
                                <div class="small-box <?= $class_bg ?>" style="border-radius:10px;">
                                    <div class="inner text-center">
                                        <h3><?= $key->materi_nm ?></h3>
                                    </div>
                                    <a <?= $click ?> href="#" class="small-box-footer <?= $a_bg ?>" style="color:black; height:50px; font-size:20px">
                                        <?= $text ?> <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="modal fade" id="modal-token">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                        <!-- <h4>Masukkan Token</h4> -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modal_body" class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="token">Token Materi</label>
                                        <input class="form-control" type="text" name="token" id="token" placeholder="Masukkan Token Materi" maxlength="6" minlength="6" autocomplete="off">
                                        <input class="form-control" type="hidden" name="group_idx" id="group_idx">
                                        <input class="form-control" type="hidden" name="materi_id" id="materi_id">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="token">Token User</label>
                                        <input class="form-control" type="text" name="tokenuser" id="tokenuser" placeholder="Masukkan Token User" maxlength="6" minlength="6" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button style="margin-top: 25px;" class="btn btn-primary" type="button" onclick="checktoken()">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->include('front/footer') ?>
    </div>
    <script src="<?= base_url() ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
        <script>
        function showtoken(group_id, materi_id) {
            window.location.href = "<?= base_url() ?>/tryout/ujian/"+materi_id+"/"+group_id;
        }

        function checktoken() {
            var token = $("#token").val();
            var group_id = $("#group_idx").val();
            var materi_id = $("#materi_id").val();
            var tokenuser = $("#tokenuser").val();
            $.ajax({
                url: "<?= base_url('token/checktoken') ?>",
                type: "post",
                dataType: "json",
                data: {
                    "token": token,
                    "group_id": group_id,
                    "materi_id": materi_id,
                    "tokenuser": tokenuser
                },
                beforeSend: function() {
                    $("#loader-wrapper").removeClass("d-none");
                },
                success: function(data) {
                    if (data == "sukses") {
                        $("#modal-token").modal("hide");
                        $("#modal-noTest").modal("show");
                        $("#group_id_notest").val(group_id);
                        $("#materi_id_notest").val(materi_id);
                        window.location.href = "<?= base_url() ?>/tryout/ujian/"+materi_id+"/"+group_id;
                    } else {
                        alert("Token salah/tidak ada, hubungi administrator");
                    }
                    $("#loader-wrapper").addClass("d-none");
                },
                error: function() {
                    alert("Error");
                    $("#loader-wrapper").addClass("d-none");
                }
            });
        }
    </script>
</body>

</html>