document.addEventListener('DOMContentLoaded', function() {
    const selectBox = document.getElementById('selectBox');
    const paymentMethodInput = document.getElementById('paymentMethod');
    const paymentConfirmation = document.getElementById('payment-confirmation');

    // 初期表示の設定
    function updateConfirmation() {
        if (!paymentMethodInput.value) {
            paymentConfirmation.textContent = "コンビニ払い"; // デフォルトの表示
        } else {
            paymentConfirmation.textContent = selectBox.textContent;
        }
    }

    // リスト項目がクリックされたときに選択を更新
    document.querySelectorAll('.select-options__list').forEach(function(option) {
        option.addEventListener('click', function() {
            // 支払い方法の確認を更新
            paymentConfirmation.textContent = this.textContent;
        });
    });

    // 初期表示を設定
    updateConfirmation();
});