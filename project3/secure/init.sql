-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables
CREATE TABLE images (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    image_name TEXT NOT NULL UNIQUE,
    image_ext TEXT NOT NULL,
    image_src TEXT NOT NULL
);

/* Source: https://www.pinterest.com/pin/335658978468805029/*/
INSERT INTO images (image_name, image_ext, image_src) VALUES ('blueberry', 'jpg', 'https://www.pinterest.com/pin/335658978468805029/');
/* Source: https://www.lovethispic.com/image/327926/cardboard-full-of-strawberries */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('strawberry', 'jpg', 'https://www.lovethispic.com/image/327926/cardboard-full-of-strawberries');
/* Source: https://www.foodfaithfitness.com/how-to-freeze-bananas/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('banana', 'jpg', 'https://www.foodfaithfitness.com/how-to-freeze-bananas/');
/* Source: https://honestcooking.com/cook-perfect-sunny-side-eggs/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('egg', 'jpg', 'https://honestcooking.com/cook-perfect-sunny-side-eggs/');
/* Source: https://www.pinterest.com/pin/175710822940658514/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('almond-butter', 'jpg', 'https://www.pinterest.com/pin/175710822940658514/');
/* Source: https://finediets.info/cottage-cheese-calories/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('cottage-cheese', 'jpg', 'https://finediets.info/cottage-cheese-calories/');
/* Source: https://acleanbake.com/how-to-make-your-own-peanut-butter/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('peanut-butter', 'jpg', 'https://acleanbake.com/how-to-make-your-own-peanut-butter/');
/* Source: https://www.latuadietapersonalizzata.it/2020/03/07/la-ricetta-della-nutella-fatta-in-casa-che-ha-soli-55-calorie-a-porzione/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('nutella', 'jpg', 'https://www.latuadietapersonalizzata.it/2020/03/07/la-ricetta-della-nutella-fatta-in-casa-che-ha-soli-55-calorie-a-porzione/');
/* Source: https://www.acouplecooks.com/avocado-recipes/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('avocado', 'jpg', 'https://www.acouplecooks.com/avocado-recipes/');
/* Source: https://www.pinterest.com/pin/91831279878060974/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('yogurt', 'jpg', 'https://www.pinterest.com/pin/91831279878060974/');
/* Source: https://www.landolakes.com/recipe/20516/toasted-garlic-parmesan-bread/ */
INSERT INTO images (image_name, image_ext, image_src) VALUES ('bread', 'jpg', 'https://www.landolakes.com/recipe/20516/toasted-garlic-parmesan-bread/');


-- TODO: initial seed data
CREATE TABLE tags (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    tag_name TEXT NOT NULL UNIQUE
);

INSERT INTO tags (tag_name) VALUES ('sweet-toast');
INSERT INTO tags (tag_name) VALUES ('fresh-toast');
INSERT INTO tags (tag_name) VALUES ('basic-toast');
INSERT INTO tags (tag_name) VALUES ('simple-toast');
INSERT INTO tags (tag_name) VALUES ('fancy-toast');
INSERT INTO tags (tag_name) VALUES ('healthy-toast');
INSERT INTO tags (tag_name) VALUES ('mild-toast');

CREATE TABLE images_tags (
    image_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    FOREIGN KEY (image_id) REFERENCES images(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);

/* bread, strawberry, nutella */
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 1);
INSERT INTO images_tags (image_id, tag_id) VALUES (2, 1);
INSERT INTO images_tags (image_id, tag_id) VALUES (8, 1);

/* bread, blueberry, yogurt */
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 2);
INSERT INTO images_tags (image_id, tag_id) VALUES (10, 2);
INSERT INTO images_tags (image_id, tag_id) VALUES (1, 2);

/* bread, banana, peanut butter */
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 3);
INSERT INTO images_tags (image_id, tag_id) VALUES (3, 3);
INSERT INTO images_tags (image_id, tag_id) VALUES (7, 3);

/* bread,  strawberry, yogurt*/
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 4);
INSERT INTO images_tags (image_id, tag_id) VALUES (2, 4);
INSERT INTO images_tags (image_id, tag_id) VALUES (10, 4);

/* bread,  blueberry, cottage cheese*/
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 5);
INSERT INTO images_tags (image_id, tag_id) VALUES (1, 5);
INSERT INTO images_tags (image_id, tag_id) VALUES (6, 5);

/* bread, egg, avocado */
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 6);
INSERT INTO images_tags (image_id, tag_id) VALUES (4, 6);
INSERT INTO images_tags (image_id, tag_id) VALUES (9, 6);

/* bread, banana, almond butter */
INSERT INTO images_tags (image_id, tag_id) VALUES (11, 7);
INSERT INTO images_tags (image_id, tag_id) VALUES (3, 7);
INSERT INTO images_tags (image_id, tag_id) VALUES (5, 7);


COMMIT;
