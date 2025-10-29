<style>
    :root {
        --bg: #eef2f7;        /* softer app background */
        --surface: #f8fafc;   /* off-white cards/surfaces */
        --muted: #6b7280;     /* gray-500 */
        --text: #111827;      /* gray-900 */
        --border: #e3e8ef;    /* soft blue-gray */
        --shadow: 0 8px 24px rgba(17, 24, 39, 0.06);

        --primary: #2563eb;   /* blue-600 */
        --primary-600: #1d4ed8;/* blue-700 */
        --success: #10b981;   /* emerald-500 */
        --success-600: #059669;
        --warning: #f59e0b;   /* amber-500 */
        --warning-600: #d97706;
        --danger: #ef4444;    /* red-500 */
        --danger-600: #dc2626;
    }

    body {
        background: var(--bg);
        background-image:
            radial-gradient(1200px 500px at -10% -10%, rgba(99, 102, 241, 0.06), transparent 60%),
            radial-gradient(900px 400px at 110% -10%, rgba(14, 165, 233, 0.05), transparent 55%);
        background-attachment: fixed;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text);
        line-height: 1.5;
    }

    /* Navbar */
    .navbar { background: var(--surface) !important; border-bottom: 1px solid var(--border); box-shadow: var(--shadow); }
    .navbar .navbar-brand { color: var(--primary) !important; font-weight: 700; letter-spacing: .2px; }
    .navbar .nav-link { color: #374151 !important; font-weight: 500; border-radius: .5rem; padding: .5rem .75rem; transition: background-color .2s ease, color .2s ease, transform .08s ease; }
    .navbar .nav-link:hover { background: #eef2ff; color: var(--primary) !important; }
    .navbar .nav-link.active { background: #eef2ff; color: var(--primary) !important; }
    .navbar .nav-link:active { transform: translateY(1px); }
    .btn-link.nav-link { color: var(--danger) !important; }
    .navbar .navbar-toggler { border-color: #d1d5db; }
    .navbar .navbar-toggler:focus { box-shadow: 0 0 0 .15rem rgba(37,99,235,.25); }

    /* Buttons */
    .btn { border-radius: 10px; font-weight: 600; letter-spacing: .2px; }
    .btn-primary { background: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-600); border-color: var(--primary-600); }
    .btn-success { background: var(--success); border-color: var(--success); }
    .btn-success:hover { background: var(--success-600); border-color: var(--success-600); }
    .btn-warning { background: var(--warning); border-color: var(--warning); color: #111827; }
    .btn-warning:hover { background: var(--warning-600); border-color: var(--warning-600); color: #111827; }
    .btn-danger { background: var(--danger); border-color: var(--danger); }
    .btn-danger:hover { background: var(--danger-600); border-color: var(--danger-600); }
    .btn-elevated { box-shadow: var(--shadow); transition: transform .15s ease, box-shadow .15s ease; }
    .btn-elevated:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(17,24,39,.1); }
    .btn:focus { box-shadow: 0 0 0 .2rem rgba(37,99,235,.2); }
    .btn .bi { margin-right: .4rem; position: relative; top: -1px; }

    /* Cards */
    .card { border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; background: var(--surface); }
    .card:hover { transform: translateY(-3px); box-shadow: 0 14px 32px rgba(17,24,39,.08); }
    .card-header { background: #f1f5f9; border-bottom-color: var(--border); font-weight: 600; }

    /* Tables */
    table { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    thead th { background: #eef2f7; color: #111827; font-weight: 700; }
    th, td { vertical-align: middle; }
    tbody tr { transition: background-color .15s ease; }
    tbody tr:hover { background: #f9fafb; }
    .table > :not(caption) > * > * { padding: .85rem 1rem; }

    /* Forms */
    .form-control { border-radius: 10px; border-color: var(--border); }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15); }

    /* Pills */
    .nav-pills .nav-link { border-radius: 10px; font-weight: 600; }
    .nav-pills .nav-link.active { background: var(--primary); }

    /* Footer */
    footer#site-footer { background: var(--surface) !important; color: var(--muted) !important; border-top: 1px solid var(--border) !important; }

    @media (max-width: 768px) {
        .navbar-brand { font-size: 1.05rem; }
        .navbar .nav-link { font-size: .95rem; }
    }
</style>
