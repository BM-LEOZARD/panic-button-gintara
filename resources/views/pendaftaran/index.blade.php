<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran - Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }

        /* ── Outer container (sama seperti index) ── */
        .outer-container {
            max-width: 1000px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.1);
            border: 1px solid #e2e8f0;
            margin: 20px auto;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrap {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: #e6f0ff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 4px 6px rgba(0, 102, 204, 0.1);
            overflow: hidden;
            padding: 8px;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            color: #1a2634;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header p {
            color: #4a5568;
            font-size: 14px;
        }

        /* ── Progress ── */
        .progress-wrap {
            display: flex;
            justify-content: center;
            position: relative;
            margin-bottom: 36px;
        }

        .progress-wrap::before {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(16.66% + 20px);
            right: calc(16.66% + 20px);
            height: 2px;
            background: #e2e8f0;
        }

        .prog-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .prog-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: all .3s;
        }

        .prog-dot.active {
            background: #0066cc;
            border-color: #0066cc;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.2);
        }

        .prog-dot.done {
            background: #22c55e;
            border-color: #22c55e;
            color: #ffffff;
        }

        .prog-label {
            color: #718096;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
        }

        .prog-label.active {
            color: #0066cc;
            font-weight: 700;
        }

        /* ── Card ── */
        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
        }

        .card-title {
            color: #1a2634;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            font-size: 20px;
            color: #0066cc;
        }

        /* ── Sections ── */
        .step-section {
            display: none;
            animation: fadeUp .35s ease;
        }

        .step-section.active {
            display: block;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ── Grid ── */
        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .grid3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .span2 {
            grid-column: span 2;
        }

        /* ── Form elements ── */
        .fg {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            color: #4a5568;
            font-size: 13px;
            font-weight: 500;
        }

        label .req {
            color: #ef4444;
        }

        input,
        select,
        textarea {
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #1a2634;
            font-size: 14px;
            font-family: inherit;
            transition: all .2s;
            width: 100%;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        input::placeholder,
        textarea::placeholder {
            color: #a0aec0;
            font-style: italic;
        }

        input[readonly],
        input:disabled {
            background: #f7fafc;
            opacity: .7;
            cursor: not-allowed;
        }

        select option {
            background: #ffffff;
            color: #1a2634;
        }

        .err {
            color: #ef4444;
            font-size: 11px;
        }

        /* ── Map ── */
        #regMap {
            height: 320px;
            border-radius: 12px;
            z-index: 1;
            border: 1px solid #e2e8f0;
        }

        .coord-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 12px;
            color: #4a5568;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .radius-alert {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #b91c1c;
            margin-top: 8px;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .radius-alert i {
            color: #ef4444;
            flex-shrink: 0;
            font-size: 15px;
            margin-top: 1px;
        }

        .radius-ok i,
        .coord-info i {
            flex-shrink: 0;
            font-size: 15px;
            margin-top: 1px;
        }

        .radius-ok {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #166534;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radius-ok i {
            color: #22c55e;
        }

        /* ── File upload ── */
        .upload-box {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all .25s;
            background: #ffffff;
        }

        .upload-box:hover {
            border-color: #0066cc;
            background: #f0f7ff;
        }

        .upload-box .u-icon i {
            font-size: 36px;
            color: #718096;
        }

        .upload-box .u-text {
            color: #1a2634;
            font-size: 14px;
            font-weight: 500;
        }

        .upload-box .u-hint {
            color: #718096;
            font-size: 12px;
            margin-top: 4px;
        }

        #ktpPreview {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
            border-radius: 8px;
            margin-top: 12px;
            display: none;
        }

        /* ── Alert validasi ── */
        .alert-err {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            color: #b91c1c;
            font-size: 13px;
        }

        .alert-err div {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .alert-err div:last-child {
            margin-bottom: 0;
        }

        .alert-err i {
            color: #ef4444;
        }

        /* ── Divider ── */
        .section-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }

        /* ── Buttons ── */
        .nav-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 28px;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .25s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-prev {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #4a5568;
        }

        .btn-prev:hover:not(:disabled) {
            border-color: #0066cc;
            color: #0066cc;
        }

        .btn-prev:disabled {
            opacity: .3;
            cursor: not-allowed;
        }

        .btn-next {
            background: #0066cc;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 102, 204, 0.2);
        }

        .btn-next:hover {
            background: #004999;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.3);
        }

        .btn-submit {
            background: #22c55e;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.2);
        }

        .btn-submit:hover {
            background: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
        }

        /* ── Footer text ── */
        .footer-text {
            text-align: center;
            color: #718096;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text a {
            color: #0066cc;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .outer-container {
                padding: 20px;
                border-radius: 16px;
                margin: 10px auto;
            }

            .grid2,
            .grid3 {
                grid-template-columns: 1fr;
            }

            .span2 {
                grid-column: span 1;
            }

            .header h1 {
                font-size: 22px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        @media (max-width: 375px) {
            .outer-container {
                padding: 16px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="outer-container">

        {{-- Header --}}
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('asset/logo.png') }}" alt="Gintara Net" />
            </div>
            <h1>Panic Button Gintara</h1>
            <p>Isi data dengan benar untuk mendaftarkan layanan panic button Gintara Net</p>
        </div>

        <div class="section-divider"></div>

        {{-- Progress --}}
        <div class="progress-wrap">
            <div class="prog-step">
                <div class="prog-dot active" id="dot1">1</div>
                <div class="prog-label active" id="lbl1">Pilih Wilayah</div>
            </div>
            <div class="prog-step">
                <div class="prog-dot" id="dot2">2</div>
                <div class="prog-label" id="lbl2">Data Diri</div>
            </div>
            <div class="prog-step">
                <div class="prog-dot" id="dot3">3</div>
                <div class="prog-label" id="lbl3">Lokasi & Dokumen</div>
            </div>
        </div>

        {{-- Error dari Laravel --}}
        @if ($errors->any())
            <div class="alert-err">
                @foreach ($errors->all() as $err)
                    <div><i class="bi bi-x-circle-fill"></i> {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data" id="regForm">
            @csrf

            {{-- STEP 1 — Pilih Wilayah --}}
            <div class="step-section active" id="section1">
                <div class="card">
                    <div class="card-title">
                        <i class="bi bi-geo-alt-fill"></i> Pilih Wilayah yang Telah Tercover
                    </div>
                    @if ($wilayah->isEmpty())
                        <p style="color:#4a5568; text-align:center; padding:24px 0;">
                            Belum ada wilayah yang tersedia. Silakan hubungi admin.
                        </p>
                    @else
                        <div class="fg">
                            <label>Pilih Wilayah <span class="req">*</span></label>
                            <select name="wilayah_cover_id" id="wilayah_cover_id" required>
                                <option value="" disabled selected>-- Pilih Wilayah --</option>
                                @foreach ($wilayah as $w)
                                    <option value="{{ $w->id }}" data-lat="{{ $w->latitude }}"
                                        data-lng="{{ $w->longtitude }}" data-radius="{{ $w->radius_meter }}"
                                        {{ old('wilayah_cover_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p id="wilayah-err" style="color:#ef4444; font-size:12px; margin-top:8px; display:none;">
                            <i class="bi bi-exclamation-triangle-fill"></i> Pilih wilayah terlebih dahulu.
                        </p>
                    @endif
                </div>
            </div>

            {{-- STEP 2 — Data Diri --}}
            <div class="step-section" id="section2">

                {{-- Identitas --}}
                <div class="card">
                    <div class="card-title">
                        <i class="bi bi-person-fill"></i> Identitas Diri
                    </div>
                    <div class="grid2">
                        <div class="fg span2">
                            <label>Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Sesuai KTP"
                                required />
                        </div>
                        <div class="fg span2">
                            <label>Username <span class="req">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                placeholder="Huruf kecil, angka, underscore" required />
                        </div>
                        <div class="fg">
                            <label>NIK <span class="req">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" placeholder="16 digit"
                                maxlength="16" inputmode="numeric" required />
                        </div>
                        <div class="fg">
                            <label>Jenis Kelamin <span class="req">*</span></label>
                            <select name="jenis_kelamin" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>Tempat Lahir <span class="req">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                placeholder="Kota kelahiran" required />
                        </div>
                        <div class="fg">
                            <label>Tanggal Lahir <span class="req">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required />
                        </div>
                        <div class="fg">
                            <label>No. HP <span class="req">*</span></label>
                            <input type="tel" name="no_hp" value="{{ old('no_hp') }}"
                                placeholder="08xxxxxxxxxx" required />
                        </div>
                        <div class="fg">
                            <label>Email <span class="req">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="contoh@email.com" required />
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="card">
                    <div class="card-title">
                        <i class="bi bi-house-fill"></i> Alamat Lengkap
                    </div>
                    <div class="grid2">
                        <div class="fg span2">
                            <label>Alamat <span class="req">*</span></label>
                            <textarea name="alamat" rows="2" placeholder="Nama jalan, gang, dll" required>{{ old('alamat') }}</textarea>
                        </div>
                        <div class="fg">
                            <label>RT <span class="req">*</span></label>
                            <input type="text" name="RT" value="{{ old('RT') }}" placeholder="001"
                                maxlength="5" required />
                        </div>
                        <div class="fg">
                            <label>RW <span class="req">*</span></label>
                            <input type="text" name="RW" value="{{ old('RW') }}" placeholder="002"
                                maxlength="5" required />
                        </div>
                        <div class="fg">
                            <label>Blok <span class="req">*</span></label>
                            <input type="text" name="GetBlockID" value="{{ old('GetBlockID') }}"
                                placeholder="Contoh: A" required />
                        </div>
                        <div class="fg">
                            <label>No. Rumah <span class="req">*</span></label>
                            <input type="text" name="GetNumber" value="{{ old('GetNumber') }}"
                                placeholder="Contoh: 12" required />
                        </div>
                        <div class="fg">
                            <label>Desa <span class="req">*</span></label>
                            <input type="text" name="desa" value="{{ old('desa') }}"
                                placeholder="Nama desa" required />
                        </div>
                        <div class="fg">
                            <label>Kelurahan <span class="req">*</span></label>
                            <input type="text" name="kelurahan" value="{{ old('kelurahan') }}"
                                placeholder="Nama kelurahan" required />
                        </div>
                        <div class="fg">
                            <label>Kecamatan <span class="req">*</span></label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                                placeholder="Nama kecamatan" required />
                        </div>
                        <div class="fg">
                            <label>Kabupaten/Kota</label>
                            <input type="text" value="Kota Cirebon" readonly disabled />
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3 — Lokasi & Dokumen --}}
            <div class="step-section" id="section3">

                {{-- Map --}}
                <div class="card">
                    <div class="card-title">
                        <i class="bi bi-map-fill"></i> Titik Lokasi Rumah
                    </div>
                    <p style="color:#4a5568; font-size:13px; margin-bottom:12px;">
                        Klik pada peta untuk menentukan titik lokasi rumah Anda. Lokasi harus berada dalam area wilayah
                        yang dipilih.
                    </p>
                    <div id="regMap"></div>

                    <div id="coordInfo" class="coord-info">
                        <i class="bi bi-pin-map"></i>
                        Belum ada titik yang dipilih. Silakan klik pada peta.
                    </div>
                    <div id="radiusAlert" class="radius-alert" style="display:none;">
                        <i class="bi bi-slash-circle-fill"></i>
                        <span>Lokasi tidak dapat dijangkau. Titik yang Anda pilih berada di luar area wilayah <strong
                                id="namaWilayahAlert"></strong>. Silakan pilih titik di dalam lingkaran biru.</span>
                    </div>
                    <div id="radiusOk" class="radius-ok" style="display:none;">
                        <i class="bi bi-check-circle-fill"></i>
                        Lokasi berada dalam jangkauan wilayah.
                    </div>

                    <input type="hidden" name="latitude" id="reg_lat" value="{{ old('latitude') }}" />
                    <input type="hidden" name="longtitude" id="reg_lng" value="{{ old('longtitude') }}" />
                </div>

                {{-- Upload KTP --}}
                <div class="card">
                    <div class="card-title">
                        <i class="bi bi-paperclip"></i> Foto KTP
                    </div>
                    <div class="upload-box" onclick="document.getElementById('ktp_file').click()">
                        <input type="file" id="ktp_file" name="foto_ktp" accept="image/jpg,image/jpeg,image/png"
                            style="display:none" required />
                        <div class="u-icon" id="uIcon"><i class="bi bi-credit-card-2-front"></i></div>
                        <div class="u-text" id="uText">Klik untuk upload foto KTP</div>
                        <div class="u-hint">Format: JPG, PNG. Maksimal 2MB</div>
                    </div>
                    <img id="ktpPreview" src="" alt="Preview KTP" />
                </div>
            </div>

            {{-- Navigasi --}}
            <div class="nav-row">
                <button type="button" class="btn btn-prev" id="prevBtn" onclick="prevStep()" disabled>
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-next" id="nextBtn" onclick="nextStep()">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-submit" id="submitBtn" style="display:none"
                    onclick="return validateBeforeSubmit()">
                    <i class="bi bi-check-lg"></i> Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            &nbsp;|&nbsp; © <span id="footer-year"></span> Gintara Net. All rights reserved.
            <script>
                document.getElementById('footer-year').textContent = new Date().getFullYear();
            </script>
        </div>

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let currentStep = 1;
        let mapReady = false;
        let regMarker = null;
        let regMap = null;

        const stepCompleted = {
            1: false,
            2: false,
            3: false
        };

        function nextStep() {
            if (currentStep === 1) {
                const sel = document.getElementById('wilayah_cover_id');
                if (!sel || !sel.value) {
                    document.getElementById('wilayah-err').style.display = 'block';
                    return;
                }
                stepCompleted[1] = true;
            }

            if (currentStep === 2) {
                const required = [
                    'name', 'nik', 'jenis_kelamin', 'no_hp', 'email', 'username',
                    'tempat_lahir', 'tanggal_lahir', 'alamat', 'RT', 'RW',
                    'GetBlockID', 'GetNumber', 'desa', 'kelurahan', 'kecamatan'
                ];
                for (const name of required) {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (el && !el.value.trim()) {
                        el.focus();
                        el.style.borderColor = '#ef4444';
                        el.style.boxShadow = '0 0 0 3px rgba(239,68,68,.25)';
                        setTimeout(() => {
                            el.style.borderColor = '';
                            el.style.boxShadow = '';
                        }, 2500);
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        return;
                    }
                }
                stepCompleted[2] = true;
            }

            goTo(currentStep + 1);
        }

        function prevStep() {
            goTo(currentStep - 1);
        }

        function goTo(step) {
            document.getElementById(`section${currentStep}`).classList.remove('active');
            currentStep = step;
            document.getElementById(`section${currentStep}`).classList.add('active');

            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById(`dot${i}`);
                const lbl = document.getElementById(`lbl${i}`);
                dot.classList.remove('active', 'done');
                lbl.classList.remove('active');

                if (i === currentStep) {
                    dot.classList.add('active');
                    lbl.classList.add('active');
                    dot.innerHTML = i;
                } else if (stepCompleted[i]) {
                    dot.classList.add('done');
                    dot.innerHTML = '<i class="bi bi-check-lg"></i>';
                } else {
                    dot.innerHTML = i;
                }
            }

            if (currentStep === 3 && !mapReady) {
                initMap();
                mapReady = true;
            }

            document.getElementById('prevBtn').disabled = currentStep === 1;
            document.getElementById('nextBtn').style.display = currentStep < 3 ? 'flex' : 'none';
            document.getElementById('submitBtn').style.display = currentStep === 3 ? 'flex' : 'none';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        const wilayahData = {
            @foreach ($wilayah as $w)
                {{ $w->id }}: {
                    nama: "{{ addslashes($w->nama) }}",
                    lat: {{ $w->latitude }},
                    lng: {{ $w->longtitude }},
                    radius: {{ $w->radius_meter }}
                },
            @endforeach
        };

        let wilayahCircle = null;
        let wilayahCenterMarker = null;
        let isInsideRadius = false;

        function initMap() {
            regMap = L.map('regMap').setView([-6.732064, 108.552273], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(regMap);

            regMap.on('click', e => placeMarker(e.latlng.lat, e.latlng.lng));

            const selId = document.getElementById('wilayah_cover_id').value;
            if (selId && wilayahData[selId]) renderWilayahCircle(wilayahData[selId]);

            const oldLat = document.getElementById('reg_lat').value;
            const oldLng = document.getElementById('reg_lng').value;
            if (oldLat && oldLng) {
                placeMarker(parseFloat(oldLat), parseFloat(oldLng));
                regMap.setView([parseFloat(oldLat), parseFloat(oldLng)], 16);
            }
        }

        function renderWilayahCircle(w) {
            if (wilayahCircle) {
                wilayahCircle.remove();
                wilayahCircle = null;
            }
            if (wilayahCenterMarker) {
                wilayahCenterMarker.remove();
                wilayahCenterMarker = null;
            }

            wilayahCircle = L.circle([w.lat, w.lng], {
                radius: w.radius,
                color: '#0066cc',
                fillColor: '#0066cc',
                fillOpacity: 0.1,
                weight: 2,
                dashArray: '6 4'
            }).addTo(regMap);

            wilayahCenterMarker = L.circleMarker([w.lat, w.lng], {
                radius: 5,
                color: '#0066cc',
                fillColor: '#0066cc',
                fillOpacity: 1,
                weight: 2
            }).addTo(regMap).bindTooltip(`Pusat: ${w.nama}`, {
                permanent: false
            });

            regMap.fitBounds(wilayahCircle.getBounds(), {
                padding: [24, 24]
            });

            if (regMarker) {
                const pos = regMarker.getLatLng();
                validateRadius(pos.lat, pos.lng);
            } else {
                resetRadiusUI();
            }
        }

        function haversine(lat1, lng1, lat2, lng2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLng / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function validateRadius(lat, lng) {
            const selId = document.getElementById('wilayah_cover_id').value;
            if (!selId || !wilayahData[selId]) {
                resetRadiusUI();
                return;
            }

            const w = wilayahData[selId];
            const dist = haversine(lat, lng, w.lat, w.lng);

            document.getElementById('coordInfo').style.display = 'none';
            document.getElementById('radiusAlert').style.display = 'none';
            document.getElementById('radiusOk').style.display = 'none';

            if (dist <= w.radius) {
                isInsideRadius = true;
                document.getElementById('radiusOk').style.display = 'flex';
            } else {
                isInsideRadius = false;
                document.getElementById('namaWilayahAlert').textContent = w.nama;
                document.getElementById('radiusAlert').style.display = 'flex';
                document.getElementById('reg_lat').value = '';
                document.getElementById('reg_lng').value = '';
            }
        }

        function resetRadiusUI() {
            isInsideRadius = false;
            document.getElementById('coordInfo').style.display = 'flex';
            document.getElementById('radiusAlert').style.display = 'none';
            document.getElementById('radiusOk').style.display = 'none';
            document.getElementById('reg_lat').value = '';
            document.getElementById('reg_lng').value = '';
        }

        function placeMarker(lat, lng) {
            if (regMarker) regMarker.remove();

            regMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="width:14px;height:14px;background:#ef4444;border:2px solid #ffffff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.1)"></div>',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                })
            }).addTo(regMap);

            const selId = document.getElementById('wilayah_cover_id').value;
            if (!selId) {
                document.getElementById('coordInfo').innerHTML =
                    '<i class="bi bi-exclamation-triangle"></i> Pilih wilayah terlebih dahulu sebelum menentukan lokasi.';
                document.getElementById('coordInfo').style.display = 'flex';
                document.getElementById('reg_lat').value = '';
                document.getElementById('reg_lng').value = '';
                return;
            }

            const w = wilayahData[selId];
            const dist = haversine(lat, lng, w.lat, w.lng);

            document.getElementById('radiusAlert').style.display = 'none';
            document.getElementById('radiusOk').style.display = 'none';
            document.getElementById('coordInfo').style.display = 'none';

            if (dist <= w.radius) {
                isInsideRadius = true;
                document.getElementById('reg_lat').value = lat.toFixed(8);
                document.getElementById('reg_lng').value = lng.toFixed(8);
                document.getElementById('radiusOk').style.display = 'flex';
            } else {
                isInsideRadius = false;
                document.getElementById('namaWilayahAlert').textContent = w.nama;
                document.getElementById('radiusAlert').style.display = 'flex';
                document.getElementById('reg_lat').value = '';
                document.getElementById('reg_lng').value = '';
            }
        }

        document.getElementById('wilayah_cover_id').addEventListener('change', function() {
            document.getElementById('wilayah-err').style.display = 'none';
            if (mapReady && wilayahData[this.value]) {
                renderWilayahCircle(wilayahData[this.value]);
                if (regMarker) {
                    regMarker.remove();
                    regMarker = null;
                }
                resetRadiusUI();
            }
        });

        function validateBeforeSubmit() {
            const lat = document.getElementById('reg_lat').value;
            const lng = document.getElementById('reg_lng').value;

            if (!lat || !lng) {
                document.getElementById('radiusAlert').style.display = 'none';
                document.getElementById('radiusOk').style.display = 'none';
                document.getElementById('coordInfo').innerHTML =
                    '<i class="bi bi-exclamation-triangle"></i> Silakan klik titik lokasi rumah Anda pada peta terlebih dahulu.';
                document.getElementById('coordInfo').style.display = 'flex';
                document.getElementById('coordInfo').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            if (!isInsideRadius) {
                document.getElementById('radiusAlert').style.display = 'flex';
                document.getElementById('radiusAlert').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            return true;
        }

        document.getElementById('ktp_file').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('uText').textContent = file.name;
            document.getElementById('uIcon').innerHTML =
                '<i class="bi bi-check-circle-fill" style="color:#22c55e"></i>';
            const preview = document.getElementById('ktpPreview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });

        @if ($errors->any())
            const errKeys = @json($errors->keys());
            const step3Keys = ['latitude', 'longtitude', 'foto_ktp'];
            const step1Keys = ['wilayah_cover_id'];

            if (errKeys.some(k => step3Keys.includes(k))) {
                stepCompleted[1] = true;
                stepCompleted[2] = true;
                goTo(3);
            } else if (errKeys.some(k => step1Keys.includes(k))) {
            } else {
                stepCompleted[1] = true;
                goTo(2);
            }
        @endif
    </script>
</body>

</html>
