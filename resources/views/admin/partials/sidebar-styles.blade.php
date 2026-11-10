<style>
    .admin-sidebar {
        position: fixed;
        z-index: 5;
        inset: 0 auto 0 0;
        display: flex;
        width: 260px;
        flex-direction: column;
        padding: 28px 18px 20px;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        color: var(--white);
        border-right: 1px solid rgba(234, 179, 8, 0.12);
        box-shadow: 4px 0 24px rgba(15, 23, 42, 0.08);
    }

    /* Bande dorée décorative en haut */
    .admin-sidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--gold) 0%, var(--emerald) 50%, var(--gold) 100%);
    }

    .sidebar-brand {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 4px;
        font-family: 'Manrope', sans-serif;
        font-size: 1.1rem;
        font-weight: 800;
        letter-spacing: -.04em;
    }
    .sidebar-brand-logo {
        width: 132px;
        height: 59px;
        padding: 3px;
        border-radius: 7px;
        background: #fff;
        object-fit: contain;
    }
    .sidebar-brand-name small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: 0;
    }
    .sidebar-brand-name span { color: var(--gold); }

    /* Séparateur sous la marque */
    .sidebar-nav {
        display: grid;
        gap: 6px;
        margin-top: 38px;
        padding-top: 22px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        position: relative;
    }
    .sidebar-nav::before {
        content: 'Navigation';
        position: absolute;
        top: -10px;
        left: 12px;
        padding: 0 8px;
        background: #15213a;
        color: rgba(255, 255, 255, 0.35);
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .sidebar-link {
        display: flex;
        min-height: 46px;
        align-items: center;
        gap: 13px;
        padding: 0 13px;
        border: 1px solid transparent;
        border-radius: 9px;
        color: rgba(255, 255, 255, 0.72);
        font-size: .83rem;
        font-weight: 600;
        transition: all .2s ease;
        position: relative;
    }
    .sidebar-link i {
        width: 18px;
        font-size: .95rem;
        color: rgba(255, 255, 255, 0.5);
        text-align: center;
        transition: color .2s ease, transform .2s ease;
    }
    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.04);
        color: var(--white);
        border-color: rgba(234, 179, 8, 0.15);
    }
    .sidebar-link:hover i {
        color: var(--gold);
        transform: scale(1.08);
    }
    .sidebar-link.active {
        background: linear-gradient(135deg, rgba(234, 179, 8, 0.16) 0%, rgba(16, 185, 129, 0.10) 100%);
        border-color: rgba(234, 179, 8, 0.35);
        color: var(--white);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06), 0 4px 12px rgba(234, 179, 8, 0.08);
    }
    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: -18px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 22px;
        border-radius: 0 3px 3px 0;
        background: linear-gradient(180deg, var(--gold), var(--emerald));
    }
    .sidebar-link.active i {
        color: var(--gold);
    }

    /* Badge compteur optionnel (pour liens avec .sidebar-badge) */
    .sidebar-badge {
        margin-left: auto;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 99px;
        background: linear-gradient(135deg, var(--emerald), #059669);
        color: #fff;
        font-size: .65rem;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
    }

    /* FOOTER */
    .sidebar-footer {
        display: grid;
        gap: 10px;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .sidebar-logout {
        display: flex;
        width: 100%;
        min-height: 44px;
        align-items: center;
        gap: 12px;
        padding: 0 13px;
        border: 1px solid rgba(244, 63, 94, 0.2);
        border-radius: 9px;
        background: rgba(244, 63, 94, 0.05);
        color: rgba(255, 255, 255, 0.8);
        font: inherit;
        font-size: .81rem;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        transition: all .2s ease;
    }
    .sidebar-logout i {
        width: 18px;
        color: #fda4af;
        text-align: center;
        transition: transform .2s ease;
    }
    .sidebar-logout:hover {
        border-color: var(--rose);
        background: rgba(244, 63, 94, 0.12);
        color: #fecdd3;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.15);
    }
    .sidebar-logout:hover i {
        color: #fff;
        transform: translateX(2px);
    }

    /* MAIN */
    .admin-main {
        min-width: 0;
        min-height: 100vh;
        margin-left: 260px;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .admin-sidebar { width: 240px; }
        .admin-main { margin-left: 240px; }
    }

    @media (max-width: 760px) {
        .admin-sidebar {
            position: static;
            width: 100%;
            min-height: 0;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-right: 0;
            border-bottom: 1px solid rgba(234, 179, 8, 0.15);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.1);
        }
        .admin-sidebar::before { height: 2px; }
        .sidebar-brand { padding: 0 4px; font-size: .98rem; }

        .sidebar-nav {
            display: flex;
            flex: 1 1 100%;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 0;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sidebar-nav::before { display: none; }

        .sidebar-link {
            min-height: 38px;
            padding: 0 10px;
            font-size: .74rem;
            gap: 8px;
            border-radius: 8px;
        }
        .sidebar-link i { width: 14px; font-size: .82rem; }
        .sidebar-link.active::before { display: none; }

        .sidebar-footer { margin-top: 0; padding-top: 0; border-top: 0; }
        .sidebar-logout {
            width: auto;
            min-height: 38px;
            padding: 0 12px;
            font-size: .74rem;
        }

        .admin-main { margin-left: 0; }
    }

    @media (max-width: 420px) {
        .sidebar-nav { gap: 4px; }
        .sidebar-link { gap: 7px; padding: 0 8px; font-size: .69rem; }
        .sidebar-logout { padding: 0 10px; font-size: .7rem; }
    }
</style>