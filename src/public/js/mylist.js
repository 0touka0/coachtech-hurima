// 初期値の登録数を取得する処理を関数として定義
function updateMylistCount(itemId, countMylist) {
	fetch(`/item/${itemId}/mylist/count`, {
		method: 'GET',
		headers: {
		'Content-Type': 'application/json',
		}
	})
	.then(response => response.json())
	.then(data => {
		countMylist.textContent = data.mylist_count; // 初期値を表示
	})
	.catch(error => console.error('Error fetching count:', error));
}

// ページロード時に登録数を取得
document.addEventListener('DOMContentLoaded', function() {
	const itemId 	  = document.getElementById('mylist').dataset.id;
	const countMylist = document.getElementById('mylist-count');

	// 初期値の登録数を取得
	updateMylistCount(itemId, countMylist);
});

// クリック時の登録・解除処理
document.getElementById('mylist').addEventListener('click', function() {
	const itemId 	  = this.dataset.id;
	const icon 		  = document.getElementById('mylist-icon');
	const countMylist = document.getElementById('mylist-count');

	fetch(`/item/${itemId}/mylist`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		}
	})

	.then(response => {
		// リダイレクトが発生していたら手動でリダイレクト
		if (response.redirected) {
			window.location.href = response.url;
			return;
		}

		return response.json();
	})
	.then(data => {
		// アイコンの切り替え
		if (data.in_mylist) {
			icon.classList.remove('fa-regular');
			icon.classList.add('fa-solid');
		} else {
			icon.classList.remove('fa-solid');
			icon.classList.add('fa-regular');
		}

		// 登録数を更新
		updateMylistCount(itemId, countMylist);
	})
	.catch(error => console.error('Error:', error));
});