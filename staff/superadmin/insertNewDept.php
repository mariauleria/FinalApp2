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

<main>
    <h2>Add New Department</h2>

    <form action="" method="post" id="add-dept">
        <ul>
            <li>
                <label for="kode-prodiv">Nama departemen baru</label> <br>
                <input type="text" name="kode-prodiv" id="kode-prodiv" required>
            </li>
            <li>
                Pihak pengelola asset <br> 
                <input type='checkbox' id='admin' name='admin' value='Admin' checked onclick='return false'><label for='admin'> Admin</label><br>
                <input type='checkbox' id='approver' name='approver' value='Approver'><label for='approver'> Approver</label><br> 
                <p>Silahkan meng-assign role pengelola asset di dashboard!</p>
            </li>
            <li>
                <button type="submit" name="submit">Add</button>
            </li>
        </ul>
    </form>
</main>