// Script interaktif BOWO: kalkulator estimasi setoran nasabah
document.addEventListener('DOMContentLoaded', function () {
    initDepositCalculator();
});

// Kalkulator estimasi nilai (Rp) dan poin pada halaman Setor Sampah
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

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = (text === null || text === undefined) ? '' : String(text);
    return div.innerHTML;
}