document.addEventListener('DOMContentLoaded', function () {
    
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('favorite-btn')) {
    
            var songId = event.target.getAttribute('data-id');
            manageFavorites(songId);
        }
    });

    function manageFavorites(songId) {
        var xhr = new XMLHttpRequest();    // AJAX request
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {

                // Update heart
                var heart = document.querySelector('.favorite-btn[data-id="' + songId + '"]');
                var isFavorite = JSON.parse(xhr.responseText).isFavorite;   // Response of manageFavorites.php
                heart.classList.toggle('fas', isFavorite);

            }
        };

        xhr.open('POST', 'manageFavorites.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('songId=' + encodeURIComponent(songId));
    }
});
