USE `touche_pas_au_klaxon`;

-- 12 agences

INSERT INTO `agence` (`nom_ville`) VALUES
('Paris'), ('Lyon'), ('Marseille'), ('Toulouse'), ('Nice'),
('Nantes'), ('Strasbourg'), ('Montpellier'), ('Bordeaux'), ('Lille'),
('Rennes'), ('Reims');

-- 20 utilisateurs dont 1 admin (Alexandre Martin)

INSERT INTO `utilisateur` (`nom`, `prenom`, `telephone`, `email`, `mot_de_passe`, `est_admin`) VALUES
('Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 1),
('Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Masson', 'Julie', '0733445566', 'julie.masson@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0),
('Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', '$2y$10$e0myzXyovK1KThZ8S1JmOeXmEbyYIez0U/GvTlyXzBf2mBfAWvBy2', 0);

-- test trajet

INSERT INTO `trajet` (`date_heure_depart`, `date_heure_arrivee`, `places_totales`, `places_disponibles`, `id_utilisateur_auteur`, `id_agence_depart`, `id_agence_arrivee`) VALUES
(NOW() + INTERVAL 2 DAY, NOW() + INTERVAL 2 DAY + INTERVAL 2 HOUR, 4, 3, 2, 1, 2),
(NOW() + INTERVAL 3 DAY, NOW() + INTERVAL 3 DAY + INTERVAL 4 HOUR, 3, 1, 3, 2, 3);