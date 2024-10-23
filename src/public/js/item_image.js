document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('.sell-item-image__btn');  // 画像選択ボタン
    const input = document.querySelector('.sell-item-image__input'); // 画像ファイル入力
    const preview = document.querySelector('.sell-item-image__image'); // プレビュー画像

    // 画像選択ボタンがクリックされたら、ファイル入力をトリガー
    button.addEventListener('click', function() {
        input.click(); // 画像選択ウィンドウを表示
    });

    // ファイルが選択された時の処理
    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            // プレビューに選択した画像を表示
            preview.src = e.target.result;
            preview.style.display = 'block'; // 画像を表示

            // ボタンを非表示にする
            button.style.display = 'none';
        };

        if (file) {
            reader.readAsDataURL(file); // 画像ファイルを読み込む
        }
    });

    // プレビュー画像がクリックされたら、再度画像を選択できるようにする
    preview.addEventListener('click', function() {
        input.click(); // 画像選択ウィンドウを再度表示
    });
});