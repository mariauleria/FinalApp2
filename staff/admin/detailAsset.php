<?php 

include './complement/header.php';

include '../backend/dbaset.php';

$category_id = $_GET['category_id'];
$query = "SELECT * FROM assets WHERE category_id = $category_id";

$result = query($query);

?>

<main>
    <div class="asset-container">
        <h2><?= "Asset Type: " . $_GET['asset_name'] ?></h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Asset ID</th>
                <th>Serial Number</th>
                <th>Current Status</th>
                <th>Stored at</th>
                <th>Current Location</th>
                <th>Brand</th>
                <th>Aksi</th>
                <th>Booked At</th>
            </tr>

            <?php $i = 1; ?>
            <?php foreach($result as $res) :?>
                <tr>
                    <td><?= $res['asset_id'] ?></td>
                    <td><?= $res['asset_sn'] ?></td>
                    <td><?= $res['asset_status'] ?></td>
                    <td><?= $res['asset_assigned_location'] ?></td>
                    <td><?= $res['asset_curr_location'] ?></td>
                    <td><?= $res['asset_brand'] ?></td>
                    <td>
                        <?php if($res['asset_status'] != 'on use') :?>
                            <a href="./editAsset.php?id=<?= htmlspecialchars($res['asset_id']) ?>">Edit</a>  <!-- DONE: buat edit page -->
                            | <a href="../backend/hapusAssetFunction.php?id=<?= htmlspecialchars($res['asset_id']) ?>" onclick="return confirm('Asset akan dihapus?');">Hapus</a>   <!-- DONE: kalau dihapus asetnya gmn? -->
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $obj = json_decode($res['asset_booked_date']);
                        if($obj){
                            $req = $obj->requests;

                            foreach($req as $re){
                                $re_id = $re->request_ID;

                                $query = "SELECT request_status FROM requests WHERE request_id = $re_id;";
                                $temp = pg_query($query);
                                $status = pg_fetch_assoc($temp);

                                if($status['request_status'] == 'done'){
                                    continue;
                                }
                                else{
                                    echo $re->book_date . " | " . $re->return_date . "<br>";
                                }
                            }
                        }
                        else{
                            echo "-";
                        }
                        
                        ?>
                    </td>
                </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </table>
    </div>
</main>