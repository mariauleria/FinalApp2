<?php

include './complement/header.php';

require './backend/pinjamAssetFunction.php';

$user_prodi = $_SESSION['curr-user']->user_kode_prodiv;
$query = "SELECT * FROM assetcategory WHERE asset_kode_prodi = '$user_prodi'";
$rows = query($query);

if(isset($_POST['submit'])){
    if(newRequest($_POST) > 0){
        echo "
        <script>
            alert('Request berhasil ditambahkan!');
            document.location.href = 'index.php';
        </script>
        ";
        exit;
    }
}

?>


<main class="asset-container">
    <h2 class="mb-4">Pinjam Asset</h2>

    <?php if(!$rows) :?>
        <h4>Tidak ada asset yang bisa dipinjam.</h4>
    <?php else: ?>
    <!-- DATE PICKER -->
        <form action="" method="post">
            <div>
                <div class="my-2">
                    <h4 class="my-3" for="datetimes">Tanggal peminjaman </h5>
                    <div class="d-flex">
                        <input class="py-2" type="text" name="datetimes" id="datetimes" size="40"/>
                        <button class="btn btn-primary mx-3" type="submit" name="check">Check</button>
                    </div>
                </div>
            </div>
        </form>
        
        <?php if(isset($_POST['check'])) :?>
            <h2 class="mt-5 mb-4">Form Peminjaman</h2>
            <form action="" method="post">
                <div>
                    <div>
                        <div>
                            <h4 class="my-3">Available Items</h4>
                            <table class="mb-5 w-100" border="1" cellpadding="10" cellspacing="0">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Aset</th>
                                    <th>Jumlah tersedia</th>
                                    <th>Jumlah dipinjam</th>
                                </tr>

                                <?php $i = 1; ?>
                                <?php foreach($rows as $row) :?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $row['asset_name']; ?></td>
                                        <td>
                                            <?php 
                                                $temp = notAvailable($row['category_id'], $_POST);
                                                $available_items = $temp[1];

                                                $avail = $row['asset_qty'] - $temp[0] - countNA($row['category_id']); 
                                                // var_dump($temp);
                                                if($avail < 0){
                                                    $avail = 0;
                                                }
                                                echo $avail; 
                                                
                                            ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="num_approver" value="<?= $row['num_approver'] ?>">
                                            <input type="hidden" name="categories[]" value="<?= $row['category_id'] ?>">
                                            <input type="hidden" name="available-items[]" value="<?= implode(",", $available_items) ?>">
                                            <input type="number" name="asset-qty[]" id="asset-qty" min="0" max="<?= $avail ?>" value="0">
                                        </td>
                                    </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="my-3">Tanggal Peminjaman</h4>
                        <table class="mb-5 w-100" border="1" cellpadding="10" cellspacing="0">
                            <tr>
                                <th>Tanggal pick up</th>
                                <th>Tanggal pengembalian</th>
                            </tr>

                            <tr>
                                <td><?= date("l, d-m-Y H:i", $book_date); ?></td>
                                <td><?= date("l, d-m-Y H:i", $return_date); ?></td>
                            </tr>
                        </table>
                    </d>
                    <div>
                        <h4 class="my-3">Alasan Peminjaman</h4>
                        <textarea  class="form-control" name="request-reason" id="request-reason" rows="5" required></textarea>
                    </d>
                    <div>
                        <input type="hidden" name="book-date" value="<?= date("Y-m-d H:i", $book_date) ?>">
                        <input type="hidden" name="return-date" value="<?= date("Y-m-d H:i", $return_date) ?>">
                        <button class="btn btn-primary my-3" type="submit" name="submit">Submit</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>


    <script>
        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                    timePicker: true,
                    startDate: moment().startOf('hour'),
                    minDate: moment().startOf('hour'),
                    endDate: moment().startOf('hour').add(32, 'hour'),
                    locale: {
                    format: 'YYYY/MM/DD hh:mm A'
                }
            });
        });
    </script>
</main>



<?php

include './complement/footer.php';

?>