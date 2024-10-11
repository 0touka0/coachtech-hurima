document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('payment-select');
    const paymentConfirmation = document.getElementById('payment-confirmation');

	function updateConfirmation() {
        const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
        if (paymentSelect.value === "") {
            paymentConfirmation.textContent = "コンビニ払い"; // デフォルトの表示
        } else {
            paymentConfirmation.textContent = selectedText;
        }
    }

    // 初期表示を設定
    updateConfirmation();

    // セレクトボックスにchangeイベントリスナーを追加
    paymentSelect.addEventListener('change', updateConfirmation);
});