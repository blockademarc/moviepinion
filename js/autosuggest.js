// Gemeinsames AJAX-Auto-Suggest für das Filmportal.
// Tag 6: Die öffentliche Suche arbeitet lokal mit der Datenbank gruppe2.
// Zusätzlich gibt es in der privaten OMDb-Maske eine zuschaltbare Komfortsuche über OMDb.
document.addEventListener('DOMContentLoaded', function () {
    const felder = document.querySelectorAll('.ajax-autosuggest');

    felder.forEach(function (feld) {
        const zielId = feld.dataset.target;
        const box = zielId ? document.getElementById(zielId) : null;
        const form = feld.closest('form');
        const quelle = feld.dataset.source || 'lokal';
        const minLength = parseInt(feld.dataset.minLength || '1', 10);
        const autoSubmit = feld.dataset.autosubmit === '1';
        const toggleId = feld.dataset.toggle || '';

        if (!box || !form) return;

        feld.addEventListener('input', function () {
            const term = feld.value.trim();
            box.innerHTML = '';

            if (term.length < minLength) return;

            if (toggleId) {
                const toggle = document.getElementById(toggleId);
                if (toggle && !toggle.checked) return;
            }

            const action = quelle === 'omdb' ? 'autosuggestOmdb' : 'autosuggestLokal';

            fetch('index.php?action=' + action + '&term=' + encodeURIComponent(term))
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    box.innerHTML = '';

                    if (!Array.isArray(data) || data.length === 0) {
                        const leer = document.createElement('div');
                        leer.className = 'vorschlag-leer';
                        leer.textContent = 'Keine Vorschläge';
                        box.appendChild(leer);
                        return;
                    }

                    data.slice(0, 8).forEach(function (eintrag) {
                        const zeile = document.createElement('div');
                        zeile.className = 'vorschlag-eintrag';

                        const typ = document.createElement('span');
                        typ.className = 'vorschlag-typ';
                        typ.textContent = eintrag.typ || quelle;

                        const text = document.createElement('span');
                        text.className = 'vorschlag-text';
                        text.textContent = eintrag.anzeige || eintrag.wert || '';

                        zeile.appendChild(typ);
                        zeile.appendChild(text);

                        zeile.addEventListener('click', function () {
                            feld.value = eintrag.wert || eintrag.anzeige || '';

                            // Bei OMDb-Vorschlägen übernehmen wir zusätzlich Jahr und IMDb-ID,
                            // weil der spätere Import dadurch genauer wird und Remakes besser unterschieden werden.
                            if (quelle === 'omdb') {
                                const jahr = form.querySelector('[name="year"]');
                                const imdbId = form.querySelector('[name="imdb_id"]');
                                if (jahr && eintrag.year) jahr.value = eintrag.year;
                                if (imdbId && eintrag.imdb_id) imdbId.value = eintrag.imdb_id;
                            }

                            box.innerHTML = '';

                            // Bei der lokalen Suche führt ein Klick direkt zur lokalen Ergebnisliste.
                            // Dadurch ist Auto-Suggest nicht nur ein Hinweis, sondern übernimmt den Suchwert wirklich.
                            if (autoSubmit) {
                                form.submit();
                            }
                        });

                        box.appendChild(zeile);
                    });
                })
                .catch(function () {
                    box.innerHTML = '';
                });
        });

        document.addEventListener('click', function (event) {
            if (!box.contains(event.target) && event.target !== feld) {
                box.innerHTML = '';
            }
        });
    });
});
