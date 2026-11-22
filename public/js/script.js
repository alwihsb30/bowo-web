// Script interaktif BOWO: estimasi setoran, toggle tabel ringkas, filter tabel, dan pencarian AJAX
document.addEventListener('DOMContentLoaded', function () {
    initDepositCalculator();
    initTableExpanders();
    initClientSearch();
    initAdminAjaxSearch();
});

// 1. Kalkulator estimasi nilai (Rp) dan poin pada halaman Setor Sampah
function initDepositCalculator() {
    var selectWaste = document.getElementById('waste_type_id');
    var inputWeight = document.getElementById('weight');
    var outputValue = document.getElementById('est_val');
    var outputPoints = document.getElementById('est_pts');
    if (!selectWaste || !inputWeight || !outputValue) return;

    function updateEstimate() {
        var opt = selectWaste.options[selectWaste.selectedIndex];
        var price = opt ? (parseFloat(opt.getAttribute('data-price')) || 0) : 0;
        var kg = Math.max(0, parseFloat(inputWeight.value) || 0);
        outputValue.textContent = 'Rp ' + Math.round(kg * price).toLocaleString('id-ID');
        if (outputPoints) {
            outputPoints.textContent = '+' + (kg > 0 ? Math.ceil(kg * 10) : 0) + ' Poin';
        }
    }

    selectWaste.addEventListener('change', updateEstimate);
    inputWeight.addEventListener('input', updateEstimate);
    updateEstimate();
}

// Helper toggle kelas .is-compact (maksimal 10 baris)
function setTableExpanded(table, btn, expand, rememberChoice) {
    if (!table) return;
    table.classList.toggle('is-compact', !expand);
    if (rememberChoice) table.dataset.expanded = expand ? '1' : '0';
    if (btn) {
        btn.textContent = expand ? 'Tampilkan ringkas' : (btn.getAttribute('data-label') || btn.textContent);
    }
}

// 2. Toggle tabel ringkas dan anchor #katalog pada Beranda
function initTableExpanders() {
    document.querySelectorAll('.js-expand-table').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var table = document.getElementById(btn.getAttribute('data-target'));
            if (table) setTableExpanded(table, btn, table.classList.contains('is-compact'), true);
        });
    });

    var catalogTable = document.getElementById('public-catalog-table');
    var catalogBtn = document.getElementById('toggle-catalog-btn');
    var navKatalog = document.getElementById('nav-katalog-link');
    if (catalogTable && window.location.hash === '#katalog') setTableExpanded(catalogTable, catalogBtn, true, true);
    if (navKatalog && catalogTable) {
        navKatalog.addEventListener('click', function () {
            setTableExpanded(catalogTable, catalogBtn, true, true);
        });
    }
}

// 3. Filter pencarian sisi klien untuk tabel publik, setoran, mutasi, dan laporan
function initClientSearch() {
    var configs = [
        ['public-waste-search', '.waste-card', 'public-catalog-table'],
        ['deposit-search-input', '.deposit-row', 'user-deposits-table'],
        ['tx-search-input', '.tx-row', 'tx-history-table'],
        ['report-search-input', '.report-row', 'admin-report-table']
    ];
    configs.forEach(function (cfg) {
        var input = document.getElementById(cfg[0]);
        if (!input) return;
        var table = document.getElementById(cfg[2]);
        var btn = document.querySelector('.js-expand-table[data-target="' + cfg[2] + '"]');

        input.addEventListener('input', function () {
            var keyword = input.value.toLowerCase().trim();
            setTableExpanded(table, btn, keyword !== '' || (table && table.dataset.expanded === '1'), false);
            document.querySelectorAll(cfg[1]).forEach(function (row) {
                var match = keyword === '' || row.textContent.toLowerCase().indexOf(keyword) !== -1;
                row.style.display = match ? '' : 'none';
            });
        });
    });
}

// 4. Pencarian AJAX jenis sampah pada panel Admin
function initAdminAjaxSearch() {
    var searchInput = document.getElementById('admin-waste-search');
    var tableBody = document.getElementById('admin-waste-tbody');
    if (!searchInput || !tableBody) return;

    var table = document.getElementById('admin-waste-table');
    var expandBtn = document.querySelector('.js-expand-table[data-target="admin-waste-table"]');

    searchInput.addEventListener('input', function () {
        var keyword = searchInput.value.trim();
        fetch(window.BASE_URL + '/index.php?c=wastetype&a=search&q=' + encodeURIComponent(keyword))
            .then(function (response) { return response.json(); })
            .then(function (result) {
                var items = result.data || [];
                if (items.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="5" class="muted-text">Data jenis sampah tidak ditemukan.</td></tr>';
                    return;
                }

                var html = '';
                for (var i = 0; i < items.length; i++) {
                    var item = items[i];
                    var cls = (i >= 10) ? ' class="extra-row"' : '';
                    var dot = (item.status === 'active')
                        ? '<span class="status-dot"><span class="dot dot-green"></span>Aktif</span>'
                        : '<span class="status-dot"><span class="dot dot-amber"></span>Nonaktif</span>';

                    html += '<tr' + cls + '>' +
                        '<td class="name-text">' + escapeHtml(item.name) + '</td>' +
                        '<td class="muted-text">' + escapeHtml(item.category) + '</td>' +
                        '<td class="mono">' + escapeHtml(item.price_formatted) + '</td>' +
                        '<td>' + dot + '</td>' +
                        '<td><div class="actions-inline">' +
                        '<a href="' + window.BASE_URL + '/index.php?c=wastetype&a=index&edit_id=' + item.id + '" class="btn-sm">Ubah</a>' +
                        '<form action="' + window.BASE_URL + '/index.php?c=wastetype&a=delete" method="POST" style="display:inline;" onsubmit="return confirm(\'Hapus jenis sampah ini?\');">' +
                        '<input type="hidden" name="id" value="' + item.id + '">' +
                        '<button type="submit" class="btn-sm">Hapus</button></form></div></td></tr>';
                }
                tableBody.innerHTML = html;
                setTableExpanded(table, expandBtn, keyword !== '' || (table && table.dataset.expanded === '1'), false);
            });
    });
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = (text === null || text === undefined) ? '' : String(text);
    return div.innerHTML;
}
