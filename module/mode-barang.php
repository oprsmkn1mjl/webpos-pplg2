<?php
if (userLogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function generateId()
{
    global $koneksi;

    $queryId = mysqli_query($koneksi, "SELECT max(id_barang) as maxid FROM tbl_barang");
    $data = mysqli_fetch_array($queryId);

    $maxid = $data['maxid'] ?? 'BRG-000'; // Default value if null
    $noUrut = (int) substr($maxid, 4, 3); // Extract the numeric part safely
    $noUrut++;
    $maxid = "BRG-" . sprintf("%03s", $noUrut); // Format the new ID

    return $maxid;
}

function insert($data)
{
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name = mysqli_real_escape_string($koneksi, $data['nama_barang']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stockmin = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode barcode sudah ada, barang gagal ditambahkan")</script>';
        return false;
    }

    //upload gambar barang
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default-brg.jpeg';
    }

    //gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqlBarang = "INSERT INTO tbl_barang VALUE ('$id', '$barcode', '$name', '$harga_beli', '$harga_jual', 0, '$satuan', '$stockmin', '$gambar')";
    mysqli_query($koneksi, $sqlBarang);
    return mysqli_affected_rows($koneksi);
}