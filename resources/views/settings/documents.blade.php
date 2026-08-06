@extends('layouts.app')

@section('title', 'Documents | EEMOT Clocking PWA')

@section('content')
<style>
    .docs-shell { display: flex; flex-direction: column; gap: 14px; }
    .docs-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px 20px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }
    .docs-hero > div:first-child {
        flex: 1 1 0;
        min-width: 0;
    }
    .docs-hero h1 { color: #fff; font-weight: 800; font-size: 1.15rem; margin: 0 0 4px; }
    .docs-hero p { color: var(--text-muted); font-size: 0.78rem; margin: 0; max-width: 75%; line-height: 1.5; }
    .docs-hero-icon {
        width: 48px; height: 48px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        flex-shrink: 0;
    }
    .docs-panel { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 18px; }
    .docs-panel h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0 0 12px; }
    .doc-list { display: grid; gap: 12px; }
    .doc-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 16px; padding: 16px 18px; }
    .doc-info strong { color: #fff; display: block; font-size: 0.96rem; margin-bottom: 4px; }
    .doc-info span { color: var(--text-dim); font-size: 0.8rem; }
    .doc-link {
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 14px;
        padding: 10px 14px;
        background: linear-gradient(135deg, #ff7a1a, #ff3d81, #7a3aff);
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .doc-link:hover { transform: translateY(-1px); opacity: 0.95; }
</style>

<div class="docs-shell">
    <section class="docs-hero">
        <div>
            <p class="eyebrow">Documents</p>
            <h1>Company Documents</h1>
            <p>Download your legal letters and policy files in one place.</p>
        </div>
        <div class="docs-hero-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
    </section>

    <section class="docs-panel">
        <h3>Available Documents</h3>
        <div class="doc-list">
            @foreach($documents as $document)
                <div class="doc-item">
                    <div class="doc-info">
                        <strong>{{ $document['title'] }}</strong>
                        <span>{{ $document['description'] }}</span>
                    </div>
                    <a href="{{ $document['file'] }}" class="doc-link" download>
                        <i class="bi bi-download"></i>
                        Download
                    </a>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection