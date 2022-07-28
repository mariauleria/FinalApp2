<?php

require 'component/header.php';



?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<main>
    <h2>Add New Department</h2>

    <form action="" method="post" id="add-dept">
        <ul>
            <li>
                <label for="kode-prodiv">Nama departemen baru</label> <br>
                <input type="text" name="kode-prodiv" id="kode-prodiv">
            </li>
            <li>
                Apakah departemen ini memiliki asset untuk di kelola? <br>
                <input type="radio" name="answer" id="ya" value="tidak"><label for="ya">Ya</label>
                <input type="radio" name="answer" id="tidak" value="ya"><label for="tidak">Tidak</label>
            </li>
            <div id="num-approver"></div>
            <li>
                <button type="submit" name="submit">Add</button>
            </li>
        </ul>

        <script>
            function newDept(id){
                const element = document.getElementById(id);
                element.innerHTML = "Pihak pengelola asset <br> <input type='checkbox' id='admin' name='admin' value='Admin' checked onclick='return false'><label for='admin'> Admin</label><br><input type='checkbox' id='approver' name='approver' value='Approver'><label for='approver'> Approver</label><br> <p>Silahkan meng-assign role pengelola asset di dashboard!</p>";
            }

            $('#add-dept input[id=ya]').on('change', function(event) {
                $('#num-approver').html(
                    newDept('num-approver')
                );
            })

            $('#add-dept input[id=tidak]').on('change', function(event) {
                $('#num-approver').html("");
            })
        </script>
    </form>
</main>