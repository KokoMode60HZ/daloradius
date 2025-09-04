<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: logo invoice";
    include('include/config/logging.php');

    $messages = [];
    $errors = [];

    $allowedMime = ['image/png','image/jpeg','image/webp'];
    $maxWidth = 400;
    $maxHeight = 100;

    function find_existing_logo(string $base): ?string {
        $candidates = [
            "images/{$base}.png",
            "images/{$base}.jpg",
            "images/{$base}.jpeg",
            "images/{$base}.webp",
        ];
        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Left logo
        if (!empty($_FILES['logo_left']['name'])) {
            if ($_FILES['logo_left']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['logo_left']['tmp_name'];
                $type = mime_content_type($tmp);
                if (!in_array($type, $allowedMime)) {
                    $errors[] = 'Logo kiri harus bertipe PNG, JPG, atau WEBP';
                } else {
                    $size = @getimagesize($tmp);
                    if (!$size) {
                        $errors[] = 'File logo kiri tidak valid';
                    } else if ($size[0] > $maxWidth || $size[1] > $maxHeight) {
                        $errors[] = 'Ukuran logo kiri melebihi 400x100 px';
                    } else {
                        $ext = $type === 'image/png' ? 'png' : ($type === 'image/webp' ? 'webp' : 'jpg');
                        $dest = "images/invoice-logo-left.$ext";
                        // remove other extensions of same base
                        foreach (['png','jpg','jpeg','webp'] as $e) {
                            $p = "images/invoice-logo-left.$e";
                            if ($p !== $dest && file_exists($p)) @unlink($p);
                        }
                        if (move_uploaded_file($tmp, $dest)) {
                            $messages[] = 'Logo kiri berhasil diunggah';
                        } else {
                            $errors[] = 'Gagal menyimpan logo kiri';
                        }
                    }
                }
            } else {
                $errors[] = 'Gagal mengunggah logo kiri';
            }
        }

        // Right logo
        if (!empty($_FILES['logo_right']['name'])) {
            if ($_FILES['logo_right']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['logo_right']['tmp_name'];
                $type = mime_content_type($tmp);
                if (!in_array($type, $allowedMime)) {
                    $errors[] = 'Logo kanan harus bertipe PNG, JPG, atau WEBP';
                } else {
                    $size = @getimagesize($tmp);
                    if (!$size) {
                        $errors[] = 'File logo kanan tidak valid';
                    } else if ($size[0] > $maxWidth || $size[1] > $maxHeight) {
                        $errors[] = 'Ukuran logo kanan melebihi 400x100 px';
                    } else {
                        $ext = $type === 'image/png' ? 'png' : ($type === 'image/webp' ? 'webp' : 'jpg');
                        $dest = "images/invoice-logo-right.$ext";
                        foreach (['png','jpg','jpeg','webp'] as $e) {
                            $p = "images/invoice-logo-right.$e";
                            if ($p !== $dest && file_exists($p)) @unlink($p);
                        }
                        if (move_uploaded_file($tmp, $dest)) {
                            $messages[] = 'Logo kanan berhasil diunggah';
                        } else {
                            $errors[] = 'Gagal menyimpan logo kanan';
                        }
                    }
                }
            } else {
                $errors[] = 'Gagal mengunggah logo kanan';
            }
        }
    }

    $leftLogo = find_existing_logo('invoice-logo-left');
    $rightLogo = find_existing_logo('invoice-logo-right');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logo Invoice - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container { max-width: 1100px; margin: 0 auto; }
        .card { background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:16px;margin-bottom:16px; }
        .title { font-size:20px;font-weight:bold;color:#333;margin-bottom:12px;border-bottom:2px solid #009688;padding-bottom:8px; }
        .form-row { display:flex;gap:24px;flex-wrap:wrap;margin-bottom:12px; }
        .form-group { flex:1;min-width:260px; }
        .label { display:block;margin-bottom:6px;color:#333;font-weight:600; }
        .note { color:#666;font-size:13px;margin:12px 0; }
        .btn-primary { background:#1e88e5;color:#fff;border:none;padding:10px 16px;border-radius:4px;cursor:pointer; }
        .btn-primary:hover { background:#1565c0; }
        .preview { margin-top:12px;display:block;max-width:400px;max-height:100px;border:1px solid #eee;padding:6px;background:#fafafa;border-radius:6px; }
        .alert { padding:10px 12px;border-radius:6px;margin:8px 0; }
        .alert-success { background:#e6f4ea;color:#1e7e34;border:1px solid #b7e1c4; }
        .alert-error { background:#fdecea;color:#b00020;border:1px solid #f5c2c7; }
    </style>
    <script>
        function previewImage(input, id) {
            const file = input.files && input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            const img = document.getElementById(id);
            img.src = url;
            img.style.display = 'block';
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Logo Invoice</h2>
        </div>

        <div class="container">
            <div class="card">
                <div class="title">Logo Invoice</div>
                <?php if (!empty($messages)) { foreach ($messages as $m) { ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($m); ?></div>
                <?php } } ?>
                <?php if (!empty($errors)) { foreach ($errors as $e) { ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($e); ?></div>
                <?php } } ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="label">Logo : KIRI</label>
                            <input type="file" name="logo_left" accept="image/png,image/jpeg,image/webp" onchange="previewImage(this, 'preview-left')">
                            <?php if ($leftLogo) { ?>
                                <img id="preview-left" class="preview" src="<?php echo htmlspecialchars($leftLogo); ?>" alt="Logo Kiri">
                            <?php } else { ?>
                                <img id="preview-left" class="preview" style="display:none" alt="Logo Kiri">
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label class="label">Logo : KANAN</label>
                            <input type="file" name="logo_right" accept="image/png,image/jpeg,image/webp" onchange="previewImage(this, 'preview-right')">
                            <?php if ($rightLogo) { ?>
                                <img id="preview-right" class="preview" src="<?php echo htmlspecialchars($rightLogo); ?>" alt="Logo Kanan">
                            <?php } else { ?>
                                <img id="preview-right" class="preview" style="display:none" alt="Logo Kanan">
                            <?php } ?>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </form>
            </div>

            <div class="card">
                <div class="note">Ukuran maksimal gambar adalah Lebar 400px dan Tinggi 100px</div>
                <div class="form-row">
                    <div class="form-group" style="flex:1 1 100%;">
                        <div class="label">Logo : KIRI</div>
                        <?php if ($leftLogo) { ?>
                            <img src="<?php echo htmlspecialchars($leftLogo); ?>" class="preview" alt="Logo Kiri">
                        <?php } else { ?>
                            <div class="note">Belum ada logo kiri yang diunggah.</div>
                        <?php } ?>
                    </div>
                    <div class="form-group" style="flex:1 1 100%;">
                        <div class="label">Logo : KANAN</div>
                        <?php if ($rightLogo) { ?>
                            <img src="<?php echo htmlspecialchars($rightLogo); ?>" class="preview" alt="Logo Kanan">
                        <?php } else { ?>
                            <div class="note">Belum ada logo kanan yang diunggah.</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>
</body>
</html>


