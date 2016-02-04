PRAGMA synchronous = OFF;
PRAGMA journal_mode = MEMORY;
BEGIN TRANSACTION;
CREATE TABLE "comments" (
  "id" int(11) NOT NULL ,
  "date" date NOT NULL,
  "FK_user_ID" int(11) NOT NULL,
  "FK_item_ID" int(11) NOT NULL,
  "text" text NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "comments" VALUES (1,'2015-11-21',1,4,'What a nice comment, gniuk gniuk!');
INSERT INTO "comments" VALUES (2,'2015-12-04',3,2,'I''m writing something about balls.');
INSERT INTO "comments" VALUES (3,'2015-12-23',5,5,'Wazzaaaaa!');
CREATE TABLE "items" (
  "id" int(11) NOT NULL ,
  "ref" varchar(56) NOT NULL,
  "FK_user_ID" int(11) NOT NULL,
  "FK_comment_ID" int(11) DEFAULT NULL,
  "date_creation" date NOT NULL,
  "content" text NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "items" VALUES (1,'itemFlask',1,NULL,'2015-02-28','["a","bit","of","json"]');
INSERT INTO "items" VALUES (2,'itemBall',3,2,'2015-07-29','{"parts":22,"type":"leather","hardness":5.314,"filled":true}');
INSERT INTO "items" VALUES (3,'itemFlow',4,NULL,'2015-07-06','[]');
INSERT INTO "items" VALUES (4,'itemNiuk',3,1,'2015-01-14','{"leski":"mow","gniuk":"gniuk"}');
INSERT INTO "items" VALUES (5,'itemZaa',5,3,'2015-12-05','[7,356,20,16]');
CREATE TABLE "users" (
  "id" int(11) NOT NULL ,
  "name" varchar(56) NOT NULL,
  "pseudo" varchar(56) NOT NULL,
  "age" int(3) NOT NULL,
  "last_action" date NOT NULL,
  "alive" tinyint(1) NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "users" VALUES (1,'Paul','Polo',34,'2016-01-14',1);
INSERT INTO "users" VALUES (2,'Marcel','Mamar',75,'2012-04-24',0);
INSERT INTO "users" VALUES (3,'Jacques','Jack',22,'2014-10-28',1);
INSERT INTO "users" VALUES (4,'Julie','Grenouille',32,'2015-10-28',1);
INSERT INTO "users" VALUES (5,'Henri','Riton',30,'2015-09-22',1);
INSERT INTO "users" VALUES (6,'Alex','AKtsuki',29,'2016-02-04',0);
INSERT INTO "users" VALUES (27,'Alex1','AK1',29,'0000-00-00',0);
CREATE UNIQUE INDEX "comments_id" ON "comments" ("id");
CREATE UNIQUE INDEX "items_id" ON "items" ("id");
CREATE UNIQUE INDEX "users_id" ON "users" ("id");
CREATE UNIQUE INDEX "users_name" ON "users" ("name");
CREATE UNIQUE INDEX "users_pseudo" ON "users" ("pseudo");
CREATE UNIQUE INDEX "items_ref" ON "items" ("ref");
END TRANSACTION; 
