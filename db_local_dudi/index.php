<?php
    header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mahasiswa</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="mhs/style.css">
</head>
<body>
    <h2>Data Mahasiswa</h2>

    <form id="mahasiswaForm">
        <input type="hidden" id="poldnim" name="poldnim">
        <label>NIM:</label>
        <input type="text" id="nim" name="nim" required>
        <label>Nama:</label>
        <input type="text" id="nama" name="nama" required>
        <label>Tempat Lahir:</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir">
        <label>Tanggal Lahir:</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir">
        <label>Jenis Kelamin:</label>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <label>Tahun Masuk:</label>
        <input type="date" id="masuk" name="masuk">
        <label>Tahun Keluar:</label>
        <input type="date" id="keluar" name="keluar">
        <button type="button" onclick="sisipMahasiswa()">Tambah</button>
        <button type="button" onclick="ubahMahasiswa()">Ubah</button>
    </form>

    <h3>Data Mahasiswa</h3>
    <button onclick="tampilMahasiswa()">Tampilkan Data</button>
    <table border="1" id="tabelMahasiswa">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        function tampilMahasiswa() {
            $.ajax({
                url: "mhs/tampil.php",
                type: "POST",
                dataType: "json",
                success: function(response) {
                    let rows = "";
                    if (response.status === "SUKSES") {
                        response.data.forEach((item) => {
                            rows += `<tr>
                                <td>${item.nim}</td>
                                <td>${item.nama}</td>
                                <td>${item.tempat_lahir}</td>
                                <td>${item.tanggal_lahir}</td>
                                <td>${item.jenis_kelamin}</td>
                                <td>${item.masuk}</td>
                                <td>${item.keluar}</td>
                                <td>
                                    <button onclick="editMahasiswa('${item.nim}', '${item.nama}', '${item.tempat_lahir}', '${item.tanggal_lahir}', '${item.jenis_kelamin}', '${item.masuk}', '${item.keluar}')">Edit</button>
                                    <button class='delete-btn' onclick="hapusMahasiswa('${item.nim}')">Hapus</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="8">${response.pesan}</td></tr>`;
                    }
                    $("#tabelMahasiswa tbody").html(rows);
                }
            });
        }

        function sisipMahasiswa() {
            let data = {
                nim: $("#nim").val(),
                nama: $("#nama").val(),
                tempat_lahir: $("#tempat_lahir").val(),
                tanggal_lahir: $("#tanggal_lahir").val(),
                jenis_kelamin: $("#jenis_kelamin").val(),
                masuk: $("#masuk").val(),
                keluar: $("#keluar").val()
            };

            $.ajax({
                url: "mhs/sisip.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function(response) {
                    alert(response.pesan);
                    if (response.status === "SUKSES") tampilMahasiswa();
                }
            });
        }

        function editMahasiswa(nim, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, masuk, keluar) {
            $("#poldnim").val(nim);
            $("#nim").val(nim);
            $("#nama").val(nama);
            $("#tempat_lahir").val(tempat_lahir);
            $("#tanggal_lahir").val(tanggal_lahir);
            $("#jenis_kelamin").val(jenis_kelamin);
            $("#masuk").val(masuk);
            $("#keluar").val(keluar);
        }

        function ubahMahasiswa() {
            let data = {
                poldnim: $("#poldnim").val(),
                nim: $("#nim").val(),
                nama: $("#nama").val(),
                tempat_lahir: $("#tempat_lahir").val(),
                tanggal_lahir: $("#tanggal_lahir").val(),
                jenis_kelamin: $("#jenis_kelamin").val(),
                masuk: $("#masuk").val(),
                keluar: $("#keluar").val()
            };

            $.ajax({
                url: "mhs/ubah.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function(response) {
                    alert(response.pesan);
                    if (response.status === "SUKSES") tampilMahasiswa();
                }
            });
        }

        function hapusMahasiswa(nim) {
            if (confirm("Yakin ingin menghapus mahasiswa ini?")) {
                $.ajax({
                    url: "mhs/hapus.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ pid: nim }),
                    success: function(response) {
                        alert(response.pesan);
                        if (response.status === "SUKSES") tampilMahasiswa();
                    }
                });
            }
        }
    </script>
</body>
</html>
