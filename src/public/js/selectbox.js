document.addEventListener('DOMContentLoaded', function() {
    const selectBox = document.getElementById('selectBox');
    const selectOptions = document.getElementById('selectOptions');
    const paymentMethodInput = document.getElementById('paymentMethod');

    // セレクトボックスのクリックでリストを表示/非表示に切り替え
    selectBox.addEventListener('click', function() {
        selectOptions.style.display = selectOptions.style.display === 'block' ? 'none' : 'block';
    });

    // リスト項目がクリックされたときの処理
    document.querySelectorAll('.select-options__list').forEach(function(option) {
        option.addEventListener('click', function() {
            // すべての項目から`selected`クラスを外す
            document.querySelectorAll('.select-options__list').forEach(function(opt) {
                opt.classList.remove('selected');
            });

            // クリックした項目に`selected`クラスを追加
            this.classList.add('selected');

            // ボックスのテキストを更新
            selectBox.textContent = this.textContent;

            // hidden inputにも選択された値をセット
            paymentMethodInput.value = this.getAttribute('data-value');

            // 選択肢リストを非表示に
            selectOptions.style.display = 'none';
        });
    });

    // ページ外をクリックしたときにリストを閉じる
    document.addEventListener('click', function(e) {
        if (!selectBox.contains(e.target) && !selectOptions.contains(e.target)) {
            selectOptions.style.display = 'none';
        }
    });
});