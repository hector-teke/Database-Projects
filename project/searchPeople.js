document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchPeople');
    var resultsContainer = document.getElementById('resultsPeople');

    searchInput.addEventListener('input', function () {
        var search = this.value.toLowerCase();

        // AJAX request
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var users = JSON.parse(xhr.responseText);
                displayResults(users);
            }
        };

        // Send request with the search input
        xhr.open('GET', 'getUserList.php?search=' + encodeURIComponent(search), true);
        xhr.send();
    });

    function displayResults(results) {
        resultsPeople.innerHTML = '';

        if (results.length === 0) {
            resultsPeople.innerHTML = 'No se encontraron resultados.';
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
        
            resultsPeople.appendChild(card);
        });
        
    }
});