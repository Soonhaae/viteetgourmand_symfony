const filtersForm = ".js-menu-filters";
// raccourci css
// exemple : filtersForm = raccourci pour la classe (puisque ".") js-menu-filters.
// On peut donc l'utiliser dans le code (v. ci-dessous plus loin) pour aller chercher l'élément html qui a cette classe.
const resultsContainer = ".js-menu-results";
const messageContainer = ".menu-filter-message";
//
//
//
//
// -----------------------------------------------------------------------------------------
function escapeHtml(value) {
    // pour éviter qu’un texte injecté dans le HTML soit interprété comme du vrai HTML ou du JS ! (donc sécurité VS code malveillant)
    // on transforme la valeur en chaîne de caractères (String) donc du texte affiché, et pas du code exécuté !
    return (
        String(value ?? "")
            // value ?? '' = ça veut dire que si value est null ou undefined, alors on met une chaîne vide à la place
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;")
    );
}
//
//
//
//
// -----------------------------------------------------------------------------------------
function renderMenu(menu) {
    // fonction qui reçoit un menu sous forme d'objet JS

    // Par exemple :
    // {
    //   title: "Menu végétarien",
    //   theme: "Été",
    //   price: 25,
    //   imageUrl: "/uploads/menu.jpg"
    // }

    // et elle va retourner le html sous forme de texte
    // Par exemple :
    //    return `
    //      <article class="public-menu-card">
    //        ...
    //      </article>
    //    `;

    // donc elle va créer le HTML pour un menu donné, en utilisant les données du menu
    // autrement dit elle transforme l'objet menu json en un bloc HTML pour l'afficher sur la page

    const image = menu.imageUrl
        ? `<img src="${escapeHtml(menu.imageUrl)}" alt="${escapeHtml(menu.imageAlt)}">`
        : "";
    // = s'il y  une image (imageUrl), on crée un élément/une balise <img> avec l'url et le texte alternatif, sinon on met une chaîne vide
    // = "condition ternaire" = condition ? valeur_si_vrai : valeur_si_faux

    return `
    <article class="public-menu-card">
      ${image}
      <div class="public-menu-card-body">
        <p class="public-menu-theme">${escapeHtml(menu.theme)}</p>
        <h2>${escapeHtml(menu.title)}</h2>
        <p>${escapeHtml(menu.content)}</p>
        <p class="public-menu-meta">
          ${escapeHtml(menu.minPersons)} pers. min. · ${escapeHtml(menu.price)} € · stock : ${escapeHtml(menu.stockAvailable)}
        </p>
      </div>
    </article>
  `;
}
// attention, comme "results.innerHTML = data.menus.map(renderMenu).join('');" utilise innerHTML, il faut être prudent :
// c’est pour ça que cette fonction renderMenu() utilise escapeHtml() sur les valeurs !
// sans escapeHtml(), si le serveur renvoie un menu avec du code malveillant (ex: <script>alert('hack')</script>), ça serait exécuté dans le navigateur de l'utilisateur !
//
//
// -----------------------------------------------------------------------------------------
function showMessage(container, message) {
    if (!container) {
        return;
    }

    container.textContent = message || "";
    container.classList.toggle("d-none", !message);
}
// fonction qui affiche ou cache un message dans un conteneur donné
// si le message existe, elle l'affiche. si le message n'existe pas, elle cache le conteneur (en ajoutant la classe d-none)
// NB : d-none = classe bootstrap qui cache l'élément (display: none)
// NB2 : on peut utiliser container.textContent = message || "" pour afficher le message ou une chaîne vide si le message est null ou undefined
// NB 3 : on peut utiliser container.classList.toggle("d-none", !message) pour ajouter ou enlever la classe d-none selon que le message existe ou pas
// NB 4 : on utilise textContent et non pas innerHTML, c'est plus sûr pour du simple texte (sinon on pourrait injecter du code malveillant dans le HTML !)
//
//
//
//
// -----------------------------------------------------------------------------------------
function initMenuFilters() {
    // FONCTION PRINCIPALE QUI S'EXECUTE QUAND LA PAGE EST CHARGEE
    // elle récupère le formulaire, le bloc où afficher les résultats, et le bloc où afficher le message
    const form = document.querySelector(filtersForm); // = aller chercher dans le html l'élément qui a cette classe (voir tout en haut avec const pour le raccourci css "filtersForm")
    const results = document.querySelector(resultsContainer);
    const message = document.querySelector(messageContainer);

    if (!form || !results || form.dataset.initialized === "true") {
        return;
    }
    // le if ici sert à vérifier :
    // - que si le formulaire n'existe pas,
    // - que si le bloc de résultats n'existe pas
    // - que si le formulaire a déjà été initialisé (dataset.initialized === "true")
    // alors on arrête/quitte  la fonction et on ne fait rien, pour éviter des erreurs ou des doublons d'événements

    form.dataset.initialized = "true";
    //
    //
    //
    //
    // -----------------------------------------------------------------------------------------
    let inputTimeout;

    const loadMenus = async () => {
        // fonction qui va demander les menus filtrés au serveur symfony
        const params = new URLSearchParams(new FormData(form)); // ça récupère toutes les valeurs du formulaire et les transforme en paramètrs d'url (ex: ?theme=été&price=25)
        const url = `${form.dataset.filterUrl}?${params.toString()}`; // ça crée l'url à appeler pour récupérer les menus filtrés, en ajoutant les paramètres d'url à l'url de base (form.dataset.filterUrl)
        //
        // exemple dans mon twig si j'ai : <form class="js-menu-filters" data-filter-url="{{ path('app_menu_filter') }}">, alors symfony fournit l'url et js ajoute les filtres derrière

        results.setAttribute("aria-busy", "true"); // on met le bloc de résultats en mode "chargement" (aria-busy = true) pour indiquer à l'utilisateur que ça charge
        //
        //
        // un TRY/CATCH pour gérer les erreurs si le fetch ne fonctionne pas (ex: pas de connexion internet, serveur down, etc.)
        try {
            // le JS appelle une route symfony en disant qu'il veut une réponse JSON
            const response = await fetch(url, {
                ///////////// ICI LE FETCH VA CHERCHER LES MENUS FILTRES SUR LE SERVEUR SYMFONY (DONC REQUETE JS / AJAX)
                // le fetch(url) = va chercher l'url et ça attend la réponse du serveur
                // le await = attendre que la réponse arrive avant de continuer le code
                //DONC en gros : attends que l serveur symfony réponde, et puis stocke cette réponse dans "response"

                headers: {
                    //  headers = pour donner des infos en plus envoyées avec la requête au serveur (ex: type de réponse attendue, token CSRF, etc.)
                    Accept: "application/json", // donc le fetch(url, { headers: { Accept: "application/json" } }) = dit au serveur qu'on veut une réponse JSON (et pas du HTML)
                },
            });

            if (!response.ok) {
                throw new Error("Impossible de filtrer les menus.");
            }

            const data = await response.json();
            // = prends la réponse reçue et transforme le json en objet JS (donc un tableau de menus filtrés + un message)
            results.innerHTML = data.menus.map(renderMenu).join(""); // renderMEnu = fonction pour transformer le tableau de menus JSON en HTML
            // donc pour chaque menu reçu, création de son html avec renderMenu, et ensuite on colle tout ensemble en mettant tout ça dans le bloc de résultats (results.innerHTML)
            // en fait donc ça remplace le contenu du bloc de résultats
            showMessage(message, data.message);
            // data.message = message du serveur (ex: "3 menus trouvés")
            // showMessage = fonction pour afficher le message dans le bloc de message / ou le cacher si pas de message
            // donc ça affiche le message du serveur dans le bloc de message / ou ça le cache si pas de message
            //
        } catch (error) {
            // si le fetch ne fonctionne pas, on attrape l'erreur et on affiche un message d'erreur
            console.error(error); // à mettre ou pas ?
            showMessage(message, "Impossible de charger les menus filtrés.");
        } finally {
            results.setAttribute("aria-busy", "false");
        }
    };
    //
    //
    //
    //
    // -----------------------------------------------------------------------------------------
    // Les EVENEMENTS du FORMULAIRE : submit, change, input, reset
    //
    // submit = quand on clique sur le bouton "Rechercher" ou qu'on appuie sur Entrée dans un champ du formulaire
    // change = quand on change la valeur d'un champ du formulaire (ex: checkbox, select, radio)
    // input = quand on tape dans un champ texte (ex: input type="text", textarea)
    // reset = quand on clique sur le bouton "Réinitialiser" du formulaire
    //
    // ces évènements sont déclenchés par l'utilisateur
    // donc on va écouter ces évènements pour lancer la fonction loadMenus() qui va appeler le serveur pour récupérer les menus filtrés

    // quand on soumet le formulaire, on empêche le rechargement classique de la page, puis on charge les menus en AJAX :
    form.addEventListener("submit", (event) => {
        event.preventDefault(); // on utilise event.preventDefault() pour empêcher le formulaire de se soumettre normalement (et donc de recharger la page)
        loadMenus();
    });

    form.addEventListener("change", loadMenus); // = quand on change un select, une checkbox, un radio, etc., ça filtre directement, sans avoir à cliquer sur le bouton "Rechercher" (submit)

    form.addEventListener("input", () => {
        window.clearTimeout(inputTimeout);
        inputTimeout = window.setTimeout(loadMenus, 300); // quand on tape dans un champ texte, ça attend 300ms avant de lancer la recherche (loadMenus)
        // ça évite d’envoyer une requête instantanément à chaque fois qu'on tape une lettre
        // ex: si on tape "m-e-n-u", ça attend 300ms après la dernière lettre tapée, comme ça on est sûr que ça ne lance pas la recherche après le m puis après le e (m-e-), etc.
    });

    form.addEventListener("reset", () => {
        window.setTimeout(loadMenus, 300); // quand on clique sur le bouton reset, ça attend 300ms avant de lancer la recherche (loadMenus) pour que le formulaire ait le temps de se réinitialiser
    });
}
//
//
//
// on lance la fonction initMenuFilters() quand la page est chargée, que ce soit normalement ou via turbo
document.addEventListener("DOMContentLoaded", initMenuFilters); // DOMContentLoaded lance le JS quand la page HTML est chargée normalement
// NB : mais Turbo peut changer de page sans vrai rechargement complet, donc DOMContentLoaded ne suffit pas toujours
// du coup on met aussi turbo:load pour que ça marche aussi quand on navigue avec turbo
// puisque turbo:load lance le JS quand la page HTML est chargée par turbo (ex: après un clic sur un lien interne)
document.addEventListener("turbo:load", initMenuFilters);
