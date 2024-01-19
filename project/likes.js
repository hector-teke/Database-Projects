document.addEventListener('DOMContentLoaded', function () {
    
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('favorite-btn') || event.target.classList.contains('favorite-btn-up')) {
    
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
                var heartUp = document.querySelector('.favorite-btn-up[data-id="' + songId + '"]'); // Recommended song on the top
                var isFavorite = JSON.parse(xhr.responseText).isFavorite;   // Response of manageFavorites.php

                if (isFavorite){
                    if(heart != null){
                        heart.classList.add('fas', 'text-danger', 'heartbeat');
                        heart.classList.remove('far');
                    }

                    if(heartUp != null){
                        heartUp.classList.add('fas', 'text-danger', 'heartbeat');
                        heartUp.classList.remove('far');
                    }
                } else {
                    if(heart != null){
                        heart.classList.add('far');
                        heart.classList.remove('fas', 'text-danger', 'heartbeat');
                    }

                    if(heartUp != null){
                        heartUp.classList.add('far');
                        heartUp.classList.remove('fas', 'text-danger', 'heartbeat');
                    }
                }

            }
        };

        xhr.open('POST', 'manageFavorites.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('songId=' + encodeURIComponent(songId));
    }
});
