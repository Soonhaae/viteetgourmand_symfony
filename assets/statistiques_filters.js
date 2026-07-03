function initStatsFilters() {
    const form = document.querySelector('.js-stats-filters');
    if (!form || form.dataset.initialized === 'true') {
        return;
    }
    form.dataset.initialized = 'true';

    const elNb = document.getElementById('stat-nb-commandes');
    const elCa = document.getElementById('stat-ca');

    let inputTimeout;

    const loadStats = async () => {
        const params = new URLSearchParams(new FormData(form));
        const url = `${form.dataset.filterUrl}?${params.toString()}`;

        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                throw new Error('Erreur lors du chargement des statistiques.');
            }

            const data = await response.json();

            if (elNb) {
                elNb.textContent = data.nbCommandes;
            }

            if (elCa) {
                elCa.textContent = data.chiffreAffaires
                    .toFixed(2)
                    .replace('.', ',')
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0') + '\u00a0€';
            }
        } catch (error) {
            console.error(error);
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        loadStats();
    });

    form.addEventListener('change', loadStats);

    form.addEventListener('reset', () => {
        window.setTimeout(loadStats, 0);
    });

    form.addEventListener('input', () => {
        window.clearTimeout(inputTimeout);
        inputTimeout = window.setTimeout(loadStats, 300);
    });
}

document.addEventListener('DOMContentLoaded', initStatsFilters);
document.addEventListener('turbo:load', initStatsFilters);
