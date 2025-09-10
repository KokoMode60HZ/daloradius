<?php
include_once('library/checklogin.php');
include_once('includes/header.php');
include_once('includes/sidebar-new.php');
$operator = isset($_SESSION['operator_user']) ? $_SESSION['operator_user'] : '';
// Dummy data, ganti dengan query ke database jika sudah ada
$data_user = [
    'nama' => 'haris',
    'email' => 'thespringbed@gmail.com',
    'ktp' => '0000000000000000',
    'hp' => '081234513746',
    'alamat' => 'singoasri',
    'username' => $operator,
    'status' => 'Active',
    'catatan' => 'Catatan',
    'foto' => 'images/default-avatar.png',
    'role' => 'Administrator',
];
?>
<div style="max-width:1100px;margin:32px auto 0 auto;background:#fff;border-radius:8px;padding:24px 32px 32px 32px;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
    <div style="font-weight:bold;margin-bottom:16px;">Informasi Akun</div>
    <div style="display:flex;align-items:center;gap:32px;margin-bottom:24px;">
        <div style="text-align:center;">
            <img src="<?php echo $data_user['foto']; ?>" alt="Foto Profil" style="width:64px;height:64px;border-radius:50%;border:2px solid #eee;">
            <div style="font-weight:bold;margin-top:8px;">HARIS</div>
            <span style="background:#43a047;color:#fff;padding:2px 10px;border-radius:6px;font-size:0.9em;">Administrator</span>
        </div>
        <div style="flex:1;text-align:right;">
            <span style="font-size:2.2em;color:#43a047;vertical-align:middle;">&#36;</span>
            <span style="font-size:1.2em;font-weight:bold;vertical-align:middle;">SISA SALDO</span>
            <span style="background:#43a047;color:#fff;padding:2px 8px;border-radius:6px;font-size:0.9em;vertical-align:middle;">TOPUP DISABLED</span>
            <div style="font-size:1.1em;margin-top:4px;">Rp.-</div>
        </div>
    </div>
    <div style="margin-bottom:16px;">Ukuran maksimal gambar adalah Lebar 200px dan Tinggi 200px</div>
    <form method="post" enctype="multipart/form-data" action="profile.php">
        <input type="file" name="foto" accept="image/*" style="margin-bottom:16px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $data_user['nama']; ?>" class="form-control" style="width:100%;margin-bottom:8px;">
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $data_user['email']; ?>" class="form-control" style="width:100%;margin-bottom:8px;">
            </div>
            <div>
                <label>KTP | SIM | Paspor</label>
                <input type="text" name="ktp" value="<?php echo $data_user['ktp']; ?>" class="form-control" style="width:100%;margin-bottom:8px;">
            </div>
            <div>
                <label>Nomor HP</label>
                <input type="text" name="hp" value="<?php echo $data_user['hp']; ?>" class="form-control" style="width:100%;margin-bottom:8px;">
            </div>
            <div style="grid-column:1/3;">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" style="width:100%;margin-bottom:8px;"><?php echo $data_user['alamat']; ?></textarea>
            </div>
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $data_user['username']; ?>" class="form-control" style="width:100%;margin-bottom:8px;" readonly>
            </div>
            <div>
                <label>Status Akun</label>
                <select name="status" class="form-control" style="width:100%;margin-bottom:8px;">
                    <option value="Active" <?php if($data_user['status']==='Active') echo 'selected'; ?>>Active</option>
                    <option value="Nonactive" <?php if($data_user['status']==='Nonactive') echo 'selected'; ?>>Nonactive</option>
                </select>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" class="form-control" style="width:100%;margin-bottom:8px;" placeholder="Kosong Untuk Tidak Merubah">
            </div>
            <div>
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" class="form-control" style="width:100%;margin-bottom:8px;" placeholder="Konfirmasi Password">
            </div>
            <div style="grid-column:1/3;">
                <label>Catatan</label>
                <input type="text" name="catatan" value="<?php echo $data_user['catatan']; ?>" class="form-control" style="width:100%;margin-bottom:8px;">
            </div>
        </div>
        <div style="margin-top:16px;">
            <button type="submit" class="btn btn-primary" style="background:#2196f3;color:#fff;padding:8px 24px;border:none;border-radius:4px;">Simpan Perubahan</button>
            <a href="index.php" style="margin-left:16px;color:#2196f3;">Or Batal</a>
        </div>
    </form>
</div>
