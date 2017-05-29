INSERT INTO user (id, username, first_name, last_name, password) VALUES
  (1, 'ndewez', 'Nicolas', 'Dewez', '$2y$13$6LQtokdHdjATxIwH4NZuxu4ynM/QYbpkTc5VBnXXM3/c8C21sULdG')
;

INSERT INTO tag (id, title, active) VALUES
  (1, 'Divers', 1),
  (2, 'PHP', 1),
  (3, 'Nginx', 1),
  (4, 'Apache', 1),
  (5, 'Serveur', 1),
  (6, 'Symfony', 1),
  (7, 'CMS', 1),
  (8, 'Dépendances', 1),
  (9, 'Composer', 1),
  (10, 'Evénement', 1),
  (11, 'Run', 1)
;

INSERT INTO post (id, title, slug, writing_date, published, updated_date) VALUES
  (1, 'Ouverture du site', 'ouverture-du-site', '2016-01-24', 1, '2016-01-24'),
  (2, 'Utiliser un serveur web pour PHP', 'utiliser-un-serveur-web-pour-php', '2016-01-25', 1, '2016-01-25'),
  (3, 'Découverte de Grav', 'decouverte-de-grav', '2016-01-29', 1, '2016-01-29'),
  (4, 'Contactez-moi', 'contactez-moi', '2016-03-10', 1, '2016-03-10'),
  (5, 'PHP en mode CLI', 'php-en-mode-cli', '2016-03-10', 1, '2016-03-10'),
  (6, 'Introduction à composer', 'introduction-a-composer', '2016-03-11', 1, '2016-03-11'),
  (7, 'Retour sur le SymfonyLive Paris 2016', 'retour-sur-le-symfonylive-paris-2016', '2016-04-13', 1, '2016-04-13'),
  (8, 'Devenir marathonien', 'devenir-marathonien', '2016-05-07', 1, '2016-05-07'),
  (9, 'Symfony', 'symfony', '2016-09-24', 1, '2016-09-24'),
  (10, 'Retour sur la SymfonyCon Berlin 2016', 'retour-sur-la-symfonycon-berlin-2016', '2016-12-06', 1, '2016-12-06'),
  (11, 'Nouvelle version du site', 'nouvelle-version-du-site', '2016-12-08', 1, '2016-12-08')
;

INSERT INTO post_tag (post_id, tag_id) VALUES
  (1, 1),
  (2, 2),
  (2, 3),
  (2, 4),
  (2, 5),
  (2, 6),
  (3, 2),
  (3, 6),
  (3, 7),
  (4, 1),
  (5, 2),
  (6, 2),
  (6, 8),
  (6, 9),
  (7, 6),
  (7, 2),
  (7, 10),
  (8, 11),
  (9, 2),
  (9, 6),
  (10, 6),
  (10, 2),
  (10, 10),
  (11, 1)
;
