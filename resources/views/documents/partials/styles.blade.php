<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0f172a">
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<style>
    :root {
        color-scheme: light;
        --ink: #0f172a;
        --muted: #64748b;
        --gold: #eab308;
        --gold-dark: #a16207;
        --gold-light: #fef9c3;
        --emerald: #10b981;
        --emerald-dark: #047857;
        --paper: #f8fafc;
        --white: #ffffff;
        --line: #e2e8f0;
        --rose: #be123c;
        --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    }
    * { box-sizing: border-box; }
    body {
        min-width: 320px; min-height: 100vh; margin: 0; padding: 24px;
        display: grid; place-items: center;
        background:
            radial-gradient(circle at 15% 15%, rgba(234, 179, 8, 0.08) 0%, transparent 45%),
            radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.06) 0%, transparent 45%),
            var(--paper);
        color: var(--ink);
        font-family: 'DM Sans', sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    a { color: inherit; text-decoration: none; }
    a:focus-visible, button:focus-visible, input:focus-visible { outline: 3px solid var(--gold); outline-offset: 3px; }
    .card {
        width: min(100%, 560px); padding: 0 40px 38px; overflow: hidden;
        border: 1px solid var(--line); border-radius: 18px;
        background: var(--white); box-shadow: var(--shadow-lg);
    }
    .brand-header {
        position: relative; display: flex; align-items: center; justify-content: center; gap: 14px;
        margin: 0 -40px 30px; padding: 22px 24px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-bottom: 1px solid rgba(234, 179, 8, 0.25);
    }
    .brand-header::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
    }
    .brand-logo { height: 38px; width: auto; object-fit: contain; }
    .brand-name { font-family: 'Manrope', sans-serif; font-size: 1.28rem; font-weight: 800; color: var(--white); letter-spacing: -.03em; }
    .brand-name span { color: var(--gold); }
    .eyebrow {
        display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 6px 12px;
        border-radius: 99px; background: linear-gradient(135deg, var(--gold-light), #fef3c7);
        color: var(--gold-dark); font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
    }
    h1 { margin: 0 0 12px; font-family: 'Manrope', sans-serif; font-size: clamp(1.4rem, 3vw, 1.75rem); font-weight: 800; letter-spacing: -.035em; line-height: 1.2; }
    .lead { margin: 0 0 22px; color: var(--muted); font-size: .92rem; line-height: 1.7; }
    .center { text-align: center; }
    .icon-badge {
        display: grid; width: 68px; height: 68px; margin: 0 auto 20px; place-items: center;
        border-radius: 20px; font-size: 1.6rem; border: 2px solid var(--white);
    }
    .icon-ok { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: var(--emerald-dark); box-shadow: 0 10px 24px rgba(16, 185, 129, 0.22); }
    .icon-warn { background: linear-gradient(135deg, var(--gold-light), #fef3c7); color: var(--gold-dark); box-shadow: 0 10px 24px rgba(234, 179, 8, 0.25); }
    .alert { display: flex; gap: 10px; margin-bottom: 18px; padding: 13px 15px; border-radius: 10px; font-size: .84rem; font-weight: 500; line-height: 1.5; }
    .alert-success { border: 1px solid #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: var(--emerald-dark); }
    .alert-error { border: 1px solid #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: var(--rose); }
    .alert-error ul { margin: 0; padding-left: 18px; }
    .doc-field { margin-bottom: 18px; padding: 16px; border: 1px solid var(--line); border-radius: 12px; background: #fcfcfd; }
    .doc-field label { display: block; margin-bottom: 8px; font-size: .88rem; font-weight: 700; }
    .reason { display: flex; gap: 8px; margin: 0 0 12px; padding: 9px 11px; border-radius: 8px; background: #fff1f2; color: var(--rose); font-size: .8rem; font-weight: 600; line-height: 1.45; }
    .doc-field input[type=file] {
        width: 100%; padding: 10px; border: 1.5px dashed #cbd5e1; border-radius: 9px;
        background: var(--white); font: inherit; font-size: .82rem;
    }
    .hint { margin: 0 0 20px; color: var(--muted); font-size: .78rem; line-height: 1.6; }
    .button {
        display: inline-flex; width: 100%; min-height: 52px; align-items: center; justify-content: center; gap: 10px;
        padding: 0 26px; border: 0; border-radius: 11px;
        background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
        color: var(--white); font: inherit; font-size: .9rem; font-weight: 700; cursor: pointer;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2); transition: all .2s ease;
    }
    .button:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.3); }
    .note { margin: 22px 0 0; color: var(--muted); font-size: .75rem; text-align: center; line-height: 1.6; }
    @media (max-width: 480px) {
        .card { padding: 0 20px 28px; }
        .brand-header { margin: 0 -20px 26px; padding: 18px 16px; }
        .brand-logo { height: 32px; }
        .brand-name { font-size: 1.1rem; }
    }
</style>