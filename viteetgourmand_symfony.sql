INSERT INTO `allergene` (`id`, `name`) VALUES
(1, 'gluten'),
(2, 'lactose'),
(3, 'poisson');

-- --------------------------------------------------------

INSERT INTO `menu` (`id`, `title`, `theme`, `regime`, `content`, `min_persons`, `price`, `conditions`, `status`) VALUES
(1, 'Menu Noël Familial', 'noel', NULL, 'Menu festif pour les fêtes de fin d’année', 6, 35.50, 'Commande 48h minimum avant', 'publie'),
(2, 'Menu Pâques Printanier', 'paques', NULL, 'Saveurs du printemps pour Pâques', 4, 28.00, 'Livraison Bordeaux intra-muros uniquement', 'publie'),
(3, 'Menu Événement Pro', 'evenement', NULL, 'Pour vos séminaires et cocktails', 10, 42.00, 'Réservation 7 jours avant', 'publie'),
(4, 'Menu Vegan Saisonnier', 'noel', NULL, '100% végétal et gourmand', 4, 32.00, 'Stock limité', 'publie');

-- --------------------------------------------------------

INSERT INTO `regime` (`id`, `name`) VALUES
(1, 'vegetarien'),
(2, 'vegan'),
(3, 'classique');

-- --------------------------------------------------------

INSERT INTO `plat` (`id`, `name`, `description`, `type`) VALUES
(1, 'Salade festive', 'Salade verte, tomates, concombres, vinaigrette maison', 'entree'),
(2, 'Quiche lorraine', 'Quiche traditionnelle', 'entree'),
(3, 'Magret de canard', 'Magret grillé, sauce porto', 'plat'),
(4, 'Gratin dauphinois', 'Pommes de terre, crème, fromage', 'plat'),
(5, 'Tarte aux fruits', 'Fruits de saison, pâte sablée', 'dessert'),
(6, 'Bouchées de butternut', 'Courge butternut à la ciboule', 'plat'),
(7, 'Curry de légumes', 'Légumes mijotés au lait de coco', 'plat'),
(8, 'Salade quinoa avocat', 'Quinoa, avocat, citron', 'entree');

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
(4, 6);

-- --------------------------------------------------------

INSERT INTO `menu_regime` (`menu_id`, `regime_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 3);

-- --------------------------------------------------------

INSERT INTO `plat_allergene` (`plat_id`, `allergene_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 3),
(4, 2),
(5, 1);
