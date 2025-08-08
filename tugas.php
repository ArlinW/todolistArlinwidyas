

<?php
date_default_timezone_set('Asia/Jakarta');
$koneksi = new mysqli("localhost", "root", "", "todo_list");

// Tambah tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama_tugas'];
    $desk = $_POST['deskripsi'];
    $prio = $_POST['prioritas'];
    $deadline = $_POST['tanggal_deadline'];
    $tanggal_sekarang = date('Y-m-d');

    if ($deadline < $tanggal_sekarang) {
        echo "<script>alert('❌ Deadline tidak boleh sebelum hari ini!'); window.location.href='tugas.php';</script>";
        exit;
    }

    $koneksi->query("INSERT INTO tugas (nama_tugas, deskripsi, prioritas, tanggal_deadline) 
                     VALUES ('$nama', '$desk', '$prio', '$deadline')");
    header("Location: tugas.php");
    exit;
}

// Ubah status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $cek = $koneksi->query("SELECT status FROM tugas WHERE id=$id")->fetch_assoc();
    $status_baru = ($cek['status'] == 'Selesai') ? 'Belum' : 'Selesai';
    $koneksi->query("UPDATE tugas SET status='$status_baru' WHERE id=$id");
    header("Location: tugas.php");
    exit;
}

// Edit deskripsi dan prioritas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_deskripsi_prioritas'])) {
    $id = $_POST['id'];
    $deskripsi_baru = $_POST['deskripsi'];
    $prioritas_baru = $_POST['prioritas'];
    $koneksi->query("UPDATE tugas SET deskripsi='$deskripsi_baru', prioritas='$prioritas_baru' WHERE id=$id");
    header("Location: tugas.php");
    exit;
}

// Hapus tugas
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM tugas WHERE id=$id");
    header("Location: tugas.php");
    exit;
}

// Ambil semua tugas
$data = $koneksi->query("SELECT * FROM tugas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to bottom right, #e0f2fe, #dbeafe);
        }
    </style>
</head>
<body class="min-h-screen py-10 px-4">

<a href="index.php" class="inline-block mb-4 bg-white border border-blue-300 text-blue-700 px-4 py-2 rounded shadow hover:bg-blue-50 transition">
    🔙 Beranda
</a>

<div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg p-6 border border-gray-300">
    <h1 class="text-3xl font-bold text-blue-700 mb-2 flex items-center gap-2">📋 Daftar Tugas</h1>
    <p class="mb-6 text-sm text-gray-600">📆 Hari ini: <strong><?= date('d-m-Y') ?></strong></p>

    <form method="POST" class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4" id="formTambahTugas">
        <input type="hidden" name="tambah" value="1">
        <div>
            <label class="block mb-1 font-semibold">📝 Nama Tugas</label>
            <input type="text" name="nama_tugas" required class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div>
            <label class="block mb-1 font-semibold">📄 Deskripsi</label>
            <input type="text" name="deskripsi" class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div>
            <label class="block mb-1 font-semibold">⚙️ Prioritas</label>
            <select name="prioritas" required class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">-- Pilih --</option>
                <option value="Penting Mendesak">Penting Mendesak</option>
                <option value="Tidak Penting Mendesak">Tidak Penting Mendesak</option>
                <option value="Penting Tidak Mendesak">Penting Tidak Mendesak</option>
                <option value="Tidak Penting Tidak Mendesak">Tidak Penting Tidak Mendesak</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 font-semibold">📅 Deadline</label>
            <input type="date" name="tanggal_deadline" id="tanggal_deadline" required class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div class="md:col-span-2 text-right">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                + Tambah Tugas
            </button>
        </div>
    </form>

    <table class="w-full table-auto border border-blue-300 text-sm md:text-base">
        <thead class="bg-blue-100 text-blue-800">
        <tr>
            <th class="border px-3 py-2">No</th>
            <th class="border px-3 py-2">Nama Tugas</th>
            <th class="border px-3 py-2">Deskripsi</th>
            <th class="border px-3 py-2">Prioritas</th>
            <th class="border px-3 py-2">Deadline</th>
            <th class="border px-3 py-2">Status</th>
            <th class="border px-3 py-2">Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data->num_rows > 0): ?>
            <?php $no = 1; while($row = $data->fetch_assoc()): ?>
                <tr class="text-center hover:bg-blue-50">
                    <td class="border px-2 py-2"><?= $no++ ?></td>
                    <td class="border px-2 py-2"><?= htmlspecialchars($row['nama_tugas']) ?></td>
                    <td class="border px-2 py-2"><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="border px-2 py-2"><?= $row['prioritas'] ?></td>
                    <td class="border px-2 py-2"><?= $row['tanggal_deadline'] ?></td>
                    <td class="border px-2 py-2">
                        <form method="GET">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="status" value="toggle">
                            <input type="checkbox" onchange="this.form.submit()" <?= $row['status'] == 'Selesai' ? 'checked' : '' ?>>
                            <span class="<?= $row['status'] == 'Selesai' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </form>
                    </td>
                    <td class="border px-2 py-2">
                        <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
                            <form method="POST" class="space-y-2 text-left">
                                <input type="hidden" name="edit_deskripsi_prioritas" value="1">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <div>
                                    <label class="text-sm">Deskripsi:</label>
                                    <input name="deskripsi" value="<?= htmlspecialchars($row['deskripsi']) ?>" class="border border-gray-300 rounded px-2 py-1 w-full">
                                </div>
                                <div>
                                    <label class="text-sm">Prioritas:</label>
                                    <select name="prioritas" class="border border-gray-300 rounded px-2 py-1 w-full">
                                        <option <?= $row['prioritas'] == 'Penting Mendesak' ? 'selected' : '' ?>>Penting Mendesak</option>
                                        <option <?= $row['prioritas'] == 'Tidak Penting Mendesak' ? 'selected' : '' ?>>Tidak Penting Mendesak</option>
                                        <option <?= $row['prioritas'] == 'Penting Tidak Mendesak' ? 'selected' : '' ?>>Penting Tidak Mendesak</option>
                                        <option <?= $row['prioritas'] == 'Tidak Penting Tidak Mendesak' ? 'selected' : '' ?>>Tidak Penting Tidak Mendesak</option>
                                    </select>
                                </div>
                                <div class="text-right">
                                    <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">💾 Simpan</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="flex gap-2 justify-center">
                                <a href="?edit=<?= $row['id'] ?>" class="text-yellow-600 hover:underline">✏️ Edit</a>
                                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus tugas ini?')" class="text-red-600 hover:underline">❌ Hapus</a>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada tugas.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Validasi Deadline dengan JS -->
<script>
document.getElementById('formTambahTugas').addEventListener('submit', function(e) {
    const inputTanggal = document.getElementById('tanggal_deadline').value;
    const today = new Date();
    const deadline = new Date(inputTanggal);

    today.setHours(0, 0, 0, 0);
    deadline.setHours(0, 0, 0, 0);

    if (deadline < today) {
        e.preventDefault();
        alert("❌ Deadline tidak boleh sebelum hari ini!");
    }
});
</script>

</body>
</html>
