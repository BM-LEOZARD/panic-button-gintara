<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
    </div>  
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active" id="notif-badge"
                        style="display:none; background:#dc2626; color:#fff;
                                 border-radius:50px; padding:1px 5px; font-size:10px;">
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="min-width:320px;">
                    <div
                        style="padding:12px 16px; border-bottom:1px solid #f1f5f9;
                                display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight:700; font-size:14px; color:#1e293b;">Notifikasi</span>
                        <button onclick="clearNotifikasi()"
                            style="font-size:11px; color:#94a3b8; background:none;
                                       border:none; cursor:pointer; padding:0;">
                            Hapus semua
                        </button>
                    </div>
                    <div class="notification-list mx-h-350 customscroll">
                        <ul id="notif-list" style="padding:0; margin:0; list-style:none;">
                            <li id="notif-empty"
                                style="padding:30px 16px; text-align:center; color:#94a3b8; font-size:13px;">
                                <div style="font-size:28px; margin-bottom:8px;">🔔</div>
                                Belum ada notifikasi
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button"
                    style="display:inline-flex; align-items:center; gap:10px;
                           cursor:default; text-decoration:none;">
                    <span class="user-icon"
                        style="display:inline-flex; align-items:center; justify-content:center;
                               width:36px; height:36px; border-radius:50%;
                               background-color:#2563eb; color:#ffffff;
                               font-weight:700; font-size:15px; text-transform:uppercase;
                               flex-shrink:0; line-height:1;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let notifTotal = 0;

    const notifIcons = {
        warning: '🚨',
        info: '📋',
        success: '✅',
        danger: '❗',
    };

    function addNotifikasi(type, title, text) {
        notifTotal++;

        const empty = document.getElementById('notif-empty');
        if (empty) empty.style.display = 'none';

        const li = document.createElement('li');
        const icon = notifIcons[type] ?? '🔔';
        const time = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        li.style.cssText = 'border-bottom:1px solid #f8fafc;';
        li.innerHTML = `
            <a href="#" style="display:block; padding:12px 16px; text-decoration:none;
                               transition:background .15s;"
               onmouseover="this.style.background='#f8fafc'"
               onmouseout="this.style.background='transparent'">
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <span style="font-size:20px; flex-shrink:0; margin-top:1px;">${icon}</span>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:13px; font-weight:700;
                                    color:#1e293b; margin-bottom:2px;">${title}</div>
                        <div style="font-size:12px; color:#64748b;
                                    white-space:nowrap; overflow:hidden;
                                    text-overflow:ellipsis;">${text}</div>
                        <div style="font-size:10px; color:#94a3b8; margin-top:4px;">${time}</div>
                    </div>
                    <span style="width:8px; height:8px; border-radius:50%;
                                 background:#dc2626; flex-shrink:0; margin-top:4px;"></span>
                </div>
            </a>
        `;

        const list = document.getElementById('notif-list');
        list.insertBefore(li, list.firstChild);

        const badge = document.getElementById('notif-badge');
        badge.textContent = notifTotal > 9 ? '9+' : notifTotal;
        badge.style.display = 'inline-block';
    }

    function clearNotifikasi() {
        notifTotal = 0;
        const list = document.getElementById('notif-list');
        const badge = document.getElementById('notif-badge');

        list.innerHTML = `
            <li id="notif-empty"
                style="padding:30px 16px; text-align:center; color:#94a3b8; font-size:13px;">
                <div style="font-size:28px; margin-bottom:8px;">🔔</div>
                Belum ada notifikasi
            </li>
        `;
        badge.style.display = 'none';
    }
</script>
