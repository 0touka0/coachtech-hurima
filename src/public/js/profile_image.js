document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('.image-form__btn');
    const input = document.querySelector('.image-form__input');
    const preview = document.querySelector('.image-form__image');

    button.addEventListener('click', function() {
        input.click();
    });

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });
});