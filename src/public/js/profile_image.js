document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('.image-form__btn');
    const input = document.querySelector('.image-form__input');
    const imageWrapper = document.querySelector('.image-form__image-wrapper');
    let preview = document.querySelector('.image-form__image');

    // ボタンをクリックしたときにファイル選択ダイアログを開く
    button.addEventListener('click', function() {
        input.click();
    });

    // ファイルが選択されたときの処理
    input.addEventListener('change', function(event) {
        const file = event.target.files[0]; // 選択されたファイル
        const reader = new FileReader();    // ファイルを読み込むオブジェクト

        reader.onload = function(e) {
            // プロフィール画像がある場合はその画像を更新
            if (preview) {
                preview.src = e.target.result; // 画像のソースを新しい画像に更新
            } else {
                // 画像がない場合は新しく<img>要素を作成して追加
                preview = document.createElement('img');
                preview.classList.add('image-form__image');
                preview.src = e.target.result; // 新しい画像を設定
                imageWrapper.innerHTML = ''; // デフォルトのグレー背景を消す
                imageWrapper.appendChild(preview); // 新しい画像を追加
            }
        };

        // ファイルが選択された場合に読み込みを開始する
        if (file) {
            reader.readAsDataURL(file);
        }
    });
});
