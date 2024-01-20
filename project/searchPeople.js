document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchPeople');
    var resultsContainer = document.getElementById('resultsPeople');
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
                var users = JSON.parse(xhr.responseText);
                displayResults(users);
            }
        };

        // Send request with the search input
        xhr.open('GET', 'getUserList.php?search=' + encodeURIComponent(input), true);
        xhr.send();
    }

    function displayResults(results) {
        resultsPeople.innerHTML = '';

        if (results.length === 0) {
            resultsPeople.innerHTML = 'No results were found.';
            return;
        }

        results.forEach(function (result) {
            
            var card = document.createElement('div');
            card.className = 'card mb-2';
        
            var cardBody = document.createElement('div');
            cardBody.className = 'card-body';
        
            var cardTitle = document.createElement('h5');
            cardTitle.className = 'card-title';
            cardTitle.textContent = result.username;
        
            var cardText = document.createElement('p');
            cardText.className = 'card-text';
            cardText.textContent = result.info;
        
            cardBody.appendChild(cardTitle);
            cardBody.appendChild(cardText);
            card.appendChild(cardBody);

            card.onclick = function() {
                // User profile
                window.location.href = 'view.php?userId=' + result.id;
            };
        
            resultsPeople.appendChild(card);
        });
        
    }
});