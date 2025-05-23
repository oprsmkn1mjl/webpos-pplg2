<?php
session_start();
if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require '../config/config.php';
require '../config/functions.php';
require '../module/mode-barang.php';

$title = 'Barang | Market PPLG';

require '../template/header.php';
require '../template/navbar.php';
require '../template/sidebar.php';

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';

if ($msg == 'deleted') {
    $id = $_GET['id'];
    $gbr = $_GET['gbr'];
    delete($id, $gbr);
    $alert = "<script>
    $(document).ready(function() {
        $(document).Toasts('create',{
            title   : 'Sukses',
            body    : 'Data barang berhasil dihapus',
            class   : 'bg-success',
            icon    : 'fas fa-check-circle',
})
});
    </script>";
}

if ($msg == 'updated') {

    $alert = "<script>
    $(document).ready(function() {
        $(document).Toasts('create',{
            title   : 'Sukses',
            body    : 'Data barang berhasil diperbarui',
            class   : 'bg-success',
            icon    : 'fas fa-check-circle',
            autohide : true,
            delay : 5000,
})
});
    </script>";
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>

                        <li class="breadcrumb-item active">Barang</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php if ($alert != '') {
                    echo $alert;
                } ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Barang</h3>
                    <a href="<?= $main_url ?>barang/form-barang.php" class="btn btn-sm btn-primary mr-2 float-right"><i
                            class="fas fa-plus"></i> Add Barang</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th style="width: 10%;">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $barang = getData("SELECT * FROM tbl_barang");
                            foreach ($barang as $b) {
                            ?>
                                <tr>
                                    <td><img src="<?= $main_url ?>assets/image/<?= $b['gambar'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                    <td><?= $b['id_barang'] ?></td>
                                    <td><?= $b['nama_barang'] ?></td>
                                    <td><?= number_format($b['harga_beli'], 0, ',', '.') ?></td>
                                    <td><?= number_format($b['harga_jual'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="form-barang.php?id=<?= $b['id_barang'] ?>&msg=update" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="?id=<?= $b['id_barang'] ?>&gbr=<?= $b['gambar'] ?>&msg=deleted"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin akan menghapus barang ini?')"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>

<?php require '../template/footer.php'; ?>