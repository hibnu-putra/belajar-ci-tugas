<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      body {
        background-color: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      }
      .table > tbody > tr > td {
        vertical-align: middle;
      }
    </style>
  </head>
  <body>
    <?php 
    function curl(){ 
        $curl = curl_init(); 
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:8080/api",
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_CUSTOMREQUEST => "GET", 
            CURLOPT_HTTPHEADER => array(
                "key: random123678abcghi", // Pastikan key ini sesuai
            ),
        ));
            
        $output = curl_exec($curl);     
        curl_close($curl);       
        
        $data = json_decode($output);   
        
        return $data;
    } 
    ?>

    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="fw-light">Dashboard - TOKO</h1>
            <p class="text-muted"><?= date("l, d-m-Y H:i:s") ?></p>
        </div> 

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Transaksi Pembelian</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Username</th>
                                <th style="width: 25%;">Alamat</th>
                                <th style="width: 15%;">Total Harga</th>
                                <th style="width: 10%;">Ongkir</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 20%;">Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $data_transaksi = curl();

                                if(isset($data_transaksi) && $data_transaksi->status->code == 200){
                                    $hasil_transaksi = $data_transaksi->results;
                                    $i = 1; 

                                    if(!empty($hasil_transaksi)){
                                        foreach($hasil_transaksi as $item){ 
                                            ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $item->username; ?></td>
                                                <td><?= $item->alamat; ?></td>
                                                <td>
                                                    <?= "Rp " . number_format($item->total_harga, 0, ',', '.'); ?><br>
                                                    <small class="text-muted">(<?= $item->jumlah_item; ?> item)</small>
                                                </td>
                                                <td><?= "Rp " . number_format($item->ongkir, 0, ',', '.'); ?></td>
                                                <td><?= ($item->status == 1) ? 'Sudah Selesai' : 'Belum Selesai'; ?></td>
                                                <td><?= date("d-m-Y H:i:s", strtotime($item->created_at)); ?></td>
                                            </tr> 
                                            <?php
                                        } 
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center py-5">Tidak ada data transaksi.</td></tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center py-5">Gagal memuat data dari API.</td></tr>';
                                }
                            ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>