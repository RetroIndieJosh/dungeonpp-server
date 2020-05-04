USE dungeonpp0;

DROP TABLE IF EXISTS MapTiles;
DROP TABLE IF EXISTS MapEnemies;
DROP TABLE IF EXISTS Maps;
DROP TABLE IF EXISTS Enemies;
DROP TABLE IF EXISTS Players;

CREATE TABLE Players (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(16),
	PRIMARY KEY (id)
);

CREATE TABLE Enemies (
	id INT AUTO_INCREMENT NOT NULL,
	power_level INT NOT NULL,
	crystals INT NOT NULL,
	x INT NOT NULL,
	y INT NOT NULL,
	owner_id INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner_id) REFERENCES Players(id)
);

CREATE TABLE Maps (
	id INT AUTO_INCREMENT NOT NULL,
        x INT NOT NULL,
        y INT NOT NULL,
	width INT NOT NULL,
	height INT NOT NULL,
        owner_id INT NOT NULL,
        is_locked BOOLEAN NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner_id) REFERENCES Players(id)
);

CREATE TABLE MapEnemies (
	map_id INT NOT NULL,
	enemy_id INT NOT NULL,
	FOREIGN KEY (map_id) REFERENCES Maps(id),
	FOREIGN KEY (enemy_id) REFERENCES Enemies(id)
);

CREATE TABLE MapTiles (
	map_id INT NOT NULL,
	pos INT NOT NULL,
	tile_id INT NOT NULL,
	FOREIGN KEY (map_id) REFERENCES Maps(id)
);

INSERT INTO Players (name) VALUES ("Joshua McLean");
INSERT INTO Maps (width, height, x, y, owner_id, is_locked) VALUES (9, 10, 0, 0, 1, false);

