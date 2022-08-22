<?php

require 'component/header.php';

//DONE: bikin function backend utk nambah new departmentnya
if(isset($_POST['submit'])){
    require 'backend/dbaset.php';

    if(addNewDept($_POST) > 0){
        echo "
        <script>
            alert('Departemen baru berhasil ditambahkan');
            document.location.href = 'index.php';
        </script>
        ";
        exit;
    }
}

?>

<main class="asset-container">
    <h2 class="mb-4">Add New Department</h2>

    <form action="" method="post" id="add-dept">
        <div>
            <div class="input-group d-flex my-4">
                <label class="w-25" for="kode-prodiv">Nama departemen baru</label> <br>
                <input type="text" name="kode-prodiv" id="kode-prodiv" required>
            </div>
            <div class="input-group d-flex my-4">
                <label class="w-25">Pihak pengelola asset</label> 
                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" value="1" for="admin" id="admin" name="admin" checked onclick='return false'>
                    <label class="form-check-label" for="admin">
                    Admin
                    </label>
                </div>
                <div class="form-check mx-4 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" value="2" id="approver" name="approver">
                    <label class="form-check-label" for="approver">
                        Approver
                    </label>
                </div>
                <!-- <p>Silahkan meng-assign role pengelola asset di dashboard!</p> -->
            </div>
            
            <div class="input-group d-flex my-4">
                <button class="btn btn-primary" type="submit" name="submit">Add</button>
            </div>
        </div>
    </form>
</main>