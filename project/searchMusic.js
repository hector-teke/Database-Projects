document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchMusic');
    var resultsContainer = document.getElementById('resultsMusic');
    search(""); //First Search

    searchInput.addEventListener('input', function () {
        var input = this.value.toLowerCase();
        search(input);
    });

    function search(input) {
        // AJAX request
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var songs = JSON.parse(xhr.responseText);
                displayResults(songs);
            }
        };

        // Send request with the search input
        xhr.open('GET', 'getMusicList.php?search=' + encodeURIComponent(input), true);
        xhr.send();
    }

    function getYoutubeThumbnail(link) {
        var videoId = link.match(/[?&]v=([^?&]+)/)[1];
        var thumbnailUrl = 'https://img.youtube.com/vi/' + videoId + '/0.jpg';
    
        return thumbnailUrl;
    }

    function displayResults(results) {
        resultsMusic.innerHTML = '';

        if (results.length === 0) {
            resultsMusic.innerHTML = 'No results were found.';
            return;
        }

        results.forEach(function (result) {
            
            var card = document.createElement('div');
            card.className = 'card mb-3';

            var row = document.createElement('div');    //row
            row.className = 'row g-0';
            card.appendChild(row);

            var thumbnailCol = document.createElement('div');   //column1 for thumbnail
            thumbnailCol.className = 'col-md-4 d-flex align-items-center';
            row.appendChild(thumbnailCol);

                var thumbnailLink = document.createElement('a');    //link
                thumbnailLink.href = result.link;
                thumbnailLink.target = '_blank';
                thumbnailCol.appendChild(thumbnailLink);

                var thumbnailImg = document.createElement('img');   //thumbnail
                thumbnailImg.src = getYoutubeThumbnail(result.link);
                thumbnailImg.className = 'card-img-top rounded';
                thumbnailImg.alt = 'Video Thumbnail';
                thumbnailLink.appendChild(thumbnailImg);

            var contentCol = document.createElement('div');     //column2 for info
            contentCol.className = 'col-md-8';
            row.appendChild(contentCol);

                var cardBody = document.createElement('div');
                cardBody.className = 'card-body';
                contentCol.appendChild(cardBody);

                var cardTitle = document.createElement('h5');   //name
                cardTitle.className = 'card-title';
                cardTitle.textContent = result.name;
                cardBody.appendChild(cardTitle);

                var cardText1 = document.createElement('p');    //artist - album
                cardText1.className = 'card-text';
                cardText1.textContent = result.artist + ' - ' + result.album;
                cardBody.appendChild(cardText1);
            
                card.onclick = function() {
                    // Music album
                    window.location.href = 'music.php?songId=' + result.id;
                };
            
        
            resultsMusic.appendChild(card);
        });
        
    }

});