// 初期値のコメント数を取得する処理を関数として定義
function updateCommentCount(itemId, countCommentElement) {
	fetch(`/item/${itemId}/comment/count`, {
		method: 'GET',
		headers: {
		'Content-Type': 'application/json',
		}
	})
	.then(response => response.json())
	.then(data => {
		countCommentElement.textContent = data.comment_count; // 初期値を表示
	})
	.catch(error => console.error('Error fetching comment count:', error));
}

// ページロード時にコメント数を取得
document.addEventListener('DOMContentLoaded', function() {
	const commentElements = document.querySelectorAll('.item__comment-count');

	commentElements.forEach(countCommentElement => {
		const itemId = countCommentElement.dataset.itemId;

		// 初期値のコメント数を取得
		updateCommentCount(itemId, countCommentElement);
	});
});