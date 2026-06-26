INSERT INTO `allergene` (`id`, `name`) VALUES
(1, 'gluten'),
(2, 'lactose'),
(3, 'poisson'),
(4, 'oeuf'),
(5, 'fruits à coque'),
(6, 'arachide'),
(7, 'soja'),
(8, 'moutarde'),
(9, 'sésame'),
(10, 'sulfites');

-- --------------------------------------------------------

INSERT INTO `menu` (`id`, `title`, `theme`, `regime`, `content`, `min_persons`, `price`, `conditions`, `status`) VALUES
(1, 'Menu Noël Familial', 'noel', NULL, 'Menu festif pour les fêtes de fin d’année', 6, 35.50, 'Commande 48h minimum avant', 'publie'),
(2, 'Menu Pâques Printanier', 'paques', NULL, 'Saveurs du printemps pour Pâques', 4, 28.00, 'Livraison Bordeaux intra-muros uniquement', 'publie'),
(3, 'Menu Événement Pro', 'evenement', NULL, 'Pour vos séminaires et cocktails', 10, 42.00, 'Réservation 7 jours avant', 'publie'),
(4, 'Menu Vegan Saisonnier', 'classique', 'Sans viande, sans poisson et sans lactose', '100% végétal et gourmand', 4, 32.00, 'Stock limité', 'publie'),
(5, 'Menu Brunch Bordelais', 'classique', 'Options végétariennes disponibles', 'Buffet sucré-salé avec pains, tartinades, bouchées fraîches et douceurs maison', 8, 24.90, 'Commande 72h minimum avant. Livraison possible le samedi et le dimanche matin.', 'publie'),
(6, 'Menu Après-midi Anniversaire', 'evenement', 'Adaptable pour enfants et adultes', 'Goûter gourmand avec pièces sucrées, boissons fraîches et bouchées faciles à partager', 10, 18.50, 'Commande 5 jours avant. Personnalisation du gâteau sur demande.', 'publie'),
(7, 'Menu Cocktail Dînatoire', 'evenement', 'Format buffet avec options sans viande', 'Assortiment de verrines, canapés, pièces chaudes et desserts individuels pour vos réceptions', 15, 39.00, 'Réservation 7 jours avant. Service sur place disponible sur devis.', 'publie'),
(8, 'Menu Végétarien Gourmand', 'classique', 'Sans viande et sans poisson', 'Menu complet autour des légumes de saison, céréales gourmandes et desserts fruités', 6, 29.50, 'Commande 72h minimum avant. Adaptation sans gluten possible selon stock.', 'publie'),
(9, 'Menu Fêtes d’Hiver', 'noel', 'Menu festif avec alternatives végétariennes', 'Recettes généreuses pour repas de fin d’année, avec entrées raffinées et desserts de saison', 8, 46.00, 'Réservation 10 jours avant. Quantités limitées en décembre.', 'publie');

-- --------------------------------------------------------

INSERT INTO `regime` (`id`, `name`) VALUES
(1, 'vegetarien'),
(2, 'vegan'),
(3, 'classique'),
(4, 'sans viande'),
(5, 'sans poisson'),
(6, 'sans lactose'),
(7, 'sans gluten'),
(8, 'sans fruits à coque'),
(9, 'sans oeuf');

-- --------------------------------------------------------

INSERT INTO `plat` (`id`, `name`, `description`, `type`) VALUES
(1, 'Salade festive', 'Salade verte, tomates, concombres, vinaigrette maison', 'entree'),
(2, 'Quiche lorraine', 'Quiche traditionnelle', 'entree'),
(3, 'Magret de canard', 'Magret grillé, sauce porto', 'plat'),
(4, 'Gratin dauphinois', 'Pommes de terre, crème, fromage', 'plat'),
(5, 'Tarte aux fruits', 'Fruits de saison, pâte sablée', 'dessert'),
(6, 'Bouchées de butternut', 'Courge butternut à la ciboule', 'plat'),
(7, 'Curry de légumes', 'Légumes mijotés au lait de coco', 'plat'),
(8, 'Salade quinoa avocat', 'Quinoa, avocat, citron', 'entree'),
(9, 'Verrines avocat tomate', 'Crème d’avocat, tomates confites, citron vert et herbes fraîches', 'entree'),
(10, 'Mini toasts saumon aneth', 'Pain toasté, saumon fumé, crème citronnée et aneth', 'entree'),
(11, 'Tartare de légumes croquants', 'Légumes de saison taillés finement, huile d’olive et herbes', 'entree'),
(12, 'Mini cakes aux légumes', 'Petits cakes moelleux aux légumes grillés', 'entree'),
(13, 'Assortiment de canapés', 'Canapés salés variés pour buffet et cocktail', 'entree'),
(14, 'Mini quiches de saison', 'Quiches individuelles aux légumes et fromage', 'plat'),
(15, 'Brochettes de volaille marinée', 'Volaille marinée aux épices douces et herbes fraîches', 'plat'),
(16, 'Parmentier de canard', 'Effiloché de canard, purée maison et jus réduit', 'plat'),
(17, 'Légumes rôtis aux herbes', 'Légumes de saison rôtis, thym et huile d’olive', 'plat'),
(18, 'Risotto crémeux aux champignons', 'Riz arborio, champignons, parmesan et bouillon parfumé', 'plat'),
(19, 'Plateau de fromages affinés', 'Sélection de fromages affinés et pain de campagne', 'plat'),
(20, 'Tartelettes aux fruits rouges', 'Tartelettes individuelles, crème légère et fruits rouges', 'dessert'),
(21, 'Madeleines et financiers', 'Assortiment de petits gâteaux moelleux', 'dessert'),
(22, 'Verrines chocolat noisette', 'Crème chocolat, éclats de noisette et biscuit croustillant', 'dessert'),
(23, 'Pavlovas aux fruits de saison', 'Meringue, crème légère et fruits frais', 'dessert'),
(24, 'Bûchette chocolat poire', 'Bûche individuelle chocolat noir et poire fondante', 'dessert'),
(25, 'Salade de fruits frais', 'Fruits frais découpés, menthe et sirop léger', 'dessert');

-- --------------------------------------------------------

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `lastname`, `firstname`, `phone`, `postal_adress`) VALUES
(1, 'raffaelli.claire@gmail.com', '[]', '$2y$13$yPSVz1GTzB6yT2aQH2YHxuOPMI0ET8ZsZsH9F1iEJhb7NdsOpnJ7O', 'Raffaelli', 'Claire', '0602474002', '7 rue cite de las cazes 34000 Montpellier');

-- --------------------------------------------------------

INSERT INTO `menu_plat` (`menu_id`, `plat_id`) VALUES
(1, 1),
(1, 2),
(1, 4),
(1, 5),
(2, 1),
(2, 3),
(2, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(4, 1),
(4, 5),
(4, 6),
(4, 7),
(4, 17),
(4, 25),
(5, 8),
(5, 9),
(5, 14),
(5, 19),
(5, 21),
(5, 25),
(6, 12),
(6, 20),
(6, 21),
(6, 22),
(6, 25),
(7, 9),
(7, 10),
(7, 13),
(7, 15),
(7, 18),
(7, 23),
(8, 11),
(8, 17),
(8, 18),
(8, 20),
(8, 25),
(9, 10),
(9, 16),
(9, 19),
(9, 23),
(9, 24);

-- --------------------------------------------------------

INSERT INTO `menu_regime` (`menu_id`, `regime_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 2),
(4, 4),
(4, 5),
(4, 6),
(5, 1),
(5, 3),
(6, 3),
(7, 3),
(7, 4),
(8, 1),
(8, 4),
(8, 5),
(9, 1),
(9, 3);

-- --------------------------------------------------------

INSERT INTO `plat_allergene` (`plat_id`, `allergene_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 3),
(4, 2),
(5, 1),
(5, 4),
(9, 10),
(10, 1),
(10, 2),
(10, 3),
(12, 1),
(12, 2),
(12, 4),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(14, 4),
(15, 8),
(16, 2),
(16, 10),
(18, 2),
(19, 2),
(20, 1),
(20, 2),
(20, 4),
(21, 1),
(21, 2),
(21, 4),
(21, 5),
(22, 2),
(22, 5),
(23, 2),
(23, 4),
(24, 1),
(24, 2),
(24, 4),
(25, 10);

-- --------------------------------------------------------

INSERT INTO `image` (`id`, `url`, `alt_text`, `menus_id`) VALUES
(1, 'img/menus/menu-brunch.png', 'Buffet brunch sucré salé avec pains, tartinades, fruits et bouchées maison', 5),
(2, 'img/menus/menu-gouter-anniversaire.png', 'Table de goûter d’anniversaire avec gâteau, douceurs et boissons fruitées', 6),
(3, 'img/menus/menu-cocktail.png', 'Cocktail dînatoire avec verrines, canapés et pièces salées variées', 7),
(4, 'img/menus/menu-vegetarien.png', 'Menu végétarien coloré avec légumes rôtis, céréales et dessert fruité', 8),
(5, 'img/menus/menu-fete.png', 'Menu de fêtes d’hiver avec entrée raffinée, plat généreux et dessert chocolaté', 9);
