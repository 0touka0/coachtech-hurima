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
            const selectedValue = this.textContent;

            // Ajaxリクエストで支払い方法をセッションに保存
            fetch('/update-payment-method', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    payment_method: selectedValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Payment method updated successfully.');

                    // 表示の部分をリアルタイムに変更
                    paymentConfirmation.textContent = `${selectedValue}`;
                } else {
                    console.error('Failed to update payment method');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // 初期表示を設定
    updateConfirmation();
});