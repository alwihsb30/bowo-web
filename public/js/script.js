// Script interaktif BOWO: estimasi setoran dan pencarian AJAX jenis sampah admin
document.addEventListener('DOMContentLoaded', function () {
    initDepositCalculator();
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

// 2. Pencarian AJAX jenis sampah pada panel Admin
function initAdminAjaxSearch() {
    var searchInput = document.getElementById('admin-waste-search');
    var tableBody = document.getElementById('admin-waste-tbody');
    if (!searchInput || !tableBody) return;

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
                    var dot = (item.status === 'active')
                        ? '<span class="status-dot"><span class="dot dot-green"></span>Aktif</span>'
                        : '<span class="status-dot"><span class="dot dot-amber"></span>Nonaktif</span>';

                    html += '<tr>' +
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
            });
    });
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = (text === null || text === undefined) ? '' : String(text);
    return div.innerHTML;
}