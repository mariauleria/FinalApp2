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


<main>
    <h2>Pinjam Aset</h2>

    <!-- DATE PICKER -->
    <form action="" method="post">
        <ul>
            <li>
                <label for="datetimes">Tanggal peminjaman: </label>
                <input type="text" name="datetimes" id="datetimes" size="38"/>
            </li>
            <li>
                <button type="submit" name="check">Check</button>
            </li>
        </ul>
    </form>
    
    <?php if(isset($_POST['check'])) :?>
        <h2>Form Peminjaman</h2>
        <form action="" method="post">
            <ul>
                <li>
                    <div class="asset-container">
                        <h3>Available Items</h3>
                        <table border="1" cellpadding="10" cellspacing="0">
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
                </li>
                <li>
                    <h3>Tanggal Peminjaman</h3>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <th>Tanggal pick up</th>
                            <th>Tanggal pengembalian</th>
                        </tr>

                        <tr>
                            <td><?= date("l, d-m-Y H:i", $book_date); ?></td>
                            <td><?= date("l, d-m-Y H:i", $return_date); ?></td>
                        </tr>
                    </table>
                </li>
                <li>
                    <h3>Alasan Peminjaman</h3>
                    <textarea name="request-reason" id="request-reason" cols="30" rows="10" required></textarea>
                </li>
                <li>
                    <input type="hidden" name="book-date" value="<?= date("Y-m-d H:i", $book_date) ?>">
                    <input type="hidden" name="return-date" value="<?= date("Y-m-d H:i", $return_date) ?>">
                    <button type="submit" name="submit">Submit</button>
                </li>
            </ul>
        </form>
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