{{-- Gaya & notifikasi bersama untuk halaman keuangan --}}
@push('styles')
<style>
.tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px
}

.tab {
    padding: 10px 18px;
    border-radius: 8px;
    border: 1px solid #8FA399;
    background: #fff;
    font-weight: 650
}

.tab.on {
    background: var(--green);
    color: #fff;
    border-color: var(--green)
}

.sel {
    min-height: 42px;
    padding: 0 12px;
    border: 1px solid #8FA399;
    border-radius: 8px;
    background: #fff;
    font: inherit;
    font-weight: 600
}

.toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center
}

.keu-table th,
.keu-table td {
    padding: 12px 10px 12px 0;
    text-align: left
}

.keu-table .right {
    text-align: right
}

.keu-table tfoot td {
    font-weight: 800;
    border-top: 2px solid var(--line)
}

.pill.off {
    background: #E5ECE7;
    color: #3B4F46
}

.pill.bad {
    background: #F3D9CF;
    color: #7A2E12
}

.inline-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
    margin-top: 10px
}

.inline-form label {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 13px;
    font-weight: 650
}

.inline-form input,
.inline-form select {
    min-height: 40px;
    padding: 0 10px;
    border: 1px solid #8FA399;
    border-radius: 8px;
    font: inherit;
    background: #fff
}

details>summary {
    cursor: pointer;
    color: var(--green);
    font-weight: 700;
    list-style: none
}

details>summary::-webkit-details-marker {
    display: none
}

.printonly {
    display: none
}

@media print {

    .sidebar,
    .noprint,
    .logout {
        display: none !important
    }

    .printonly {
        display: block
    }

    .main {
        padding: 0
    }

    .card {
        border: 0;
        padding: 0
    }
}
</style>
@endpush

@if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="notice error">{{ session('error') }}</div>@endif
@if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif