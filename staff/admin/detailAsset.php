<?php 

include './complement/header.php';

include '../backend/dbaset.php';

$category_id = $_GET['category_id'];
$query = "SELECT * FROM assets WHERE category_id = $category_id";

$result = query($query);

?>

<main class="asset-container">
    <div>
        <h2 class="mb-4"><?= "Asset Type: " . $_GET['asset_name'] ?></h2>
        <table class="table text-center" cellpadding="10" cellspacing="0">
            <tr class="row">
                <th class="col-1">Asset ID</th>
                <th class="col-2">Serial Number</th>
                <th class="col-1">Current Status</th>
                <th class="col-1">PIC</th>
                <th class="col-2">Current Location</th>
                <th class="col-1">Stored at</th>
                <th class="col-1">Brand</th>
                <th class="col-2">Aksi</th>
                <th class="col-1">Booked At</th>
            </tr>

            <?php $i = 1; ?>
            <?php foreach($result as $res) :?>
                <tr class="row">
                    <td class="col-1"><?= $res['asset_id'] ?></td>
                    <td class="col-2"><?= $res['asset_sn'] ?></td>
                    <td class="col-1"><?= $res['asset_status'] ?></td>
                    <td class="col-1">
                    <?php if($res['asset_status'] == 'on use') :?>
                        <?php 
                            $ob = json_decode($res['asset_booked_date']);
                            $ob = $ob->requests;

                            foreach($ob as $o){
                                $temps = $o->request_ID;

                                $query = "SELECT request_status, user_id FROM requests WHERE request_id = $temps;";
                                $temp = pg_query($query);
                                $status = pg_fetch_assoc($temp);

                                if($status['request_status'] == 'on use'){
                                    $id = $status['user_id'];

                                    $query = "SELECT username FROM users WHERE user_id = $id;";
                                    $temp = pg_query($query);
                                    $status = pg_fetch_assoc($temp);

                                    echo $status['username'];
                                    break;
                                }
                            }
                        ?>
                    <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td class="col-2"><?= $res['asset_curr_location'] ?></td>
                    <td class="col-1"><?= $res['asset_assigned_location'] ?></td>
                    <td class="col-1"><?= $res['asset_brand'] ?></td>
                    <td class="col-2">
                        <?php if($res['asset_status'] != 'on use') :?>
                            <a class="btn btn-primary btn-sm" href="./editAsset.php?id=<?= htmlspecialchars($res['asset_id']) ?>">Edit</a>  <!-- DONE: buat edit page -->
                            <!-- DONE: Kalau hapus asetnya request yg bookingnya gimana? arahin ke delete requestnya? -->
                            | <a class="btn btn-primary btn-sm" href="../backend/hapusAssetFunction.php?id=<?= htmlspecialchars($res['asset_id']) ?>" onclick="return confirm('Asset akan dihapus?');">Hapus</a>   <!-- DONE: kalau dihapus asetnya gmn? -->
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="col-1">
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
                        // else{
                        //     echo "-";
                        // }
                        
                        ?>
                    </td>
                </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </table>
    </div>
</main>