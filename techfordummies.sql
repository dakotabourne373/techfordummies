CREATE DATABASE techfordummies;
USE techfordummies;
-- USE db2nb;
CREATE TABLE `User`(
    uid int NOT NULL AUTO_INCREMENT,
    username varchar(15) NOT NULL,
    password text NOT NULL,
    join_date DATETIME NOT NULL default now(),
    total_posts int NOT NULL default 0,
    bio text,
    PRIMARY KEY (uid)
);
CREATE TABLE `Category`(
    catID int NOT NULL AUTO_INCREMENT,
    cname varchar(20) NOT NULL,
    PRIMARY KEY(catID)
);
CREATE TABLE `Post`(
    pid int NOT NULL AUTO_INCREMENT,
    uid int NOT NULL,
    date_posted DATETIME NOT NULL default now(),
    catID int NOT NULL,
    title text NOT NULL,
    text text NOT NULL,
    total_likes int default 0,
    PRIMARY KEY(pid),
    FOREIGN KEY(uid) REFERENCES User(uid) 
    ON DELETE CASCADE,
    FOREIGN KEY(catID) REFERENCES Category(catID)
);
CREATE TABLE `Comment`(
    cid int NOT NULL AUTO_INCREMENT,
	pid int NOT NULL,
    date_posted DATETIME NOT NULL default now(),
    text text NOT NULL,
    total_likes int default 0,
    PRIMARY KEY(cid),
    FOREIGN KEY(pid) REFERENCES Post(pid)
    ON DELETE CASCADE
);
CREATE TABLE `UserComments`(
	uid INT NOT NULL,
    cid INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(cid) REFERENCES Comment(cid) ON DELETE CASCADE
);
CREATE TABLE `CatLikes`(
	uid INT NOT NULL,
    catID INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(catID) REFERENCES Category(catID) ON DELETE CASCADE
);
CREATE TABLE `UserLikes`(
	uid INT NOT NULL,
    pid INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(pid) REFERENCES Post(pid) ON DELETE CASCADE
);
CREATE TABLE `CommentLikes`(
	uid INT NOT NULL,
    cid INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(cid) REFERENCES Comment(cid) ON DELETE CASCADE
);
CREATE TABLE `Bookmarks`(
	uid INT NOT NULL,
    pid INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(pid) REFERENCES Post(pid) ON DELETE CASCADE
);
CREATE TABLE `FollowedUsers`(
	uid INT NOT NULL,
    fuid INT NOT NULL,
    FOREIGN KEY(uid) REFERENCES User(uid) ON DELETE CASCADE,
    FOREIGN KEY(fuid) REFERENCES User(uid) ON DELETE CASCADE
);

CREATE TABLE `User_Audit` (
    log_date date NOT NULL,
    who_update varchar(30) NOT NULL,
    join_date date DEFAULT NULL,
    uid int(11) DEFAULT NULL,
    username varchar(15) NOT NULL,
    total_posts int NOT NULL default 0,
    old_bio text NOT NULL,
    new_bio text NOT NULL
);

DELIMITER $$
CREATE TRIGGER userliked AFTER INSERT ON UserLikes
FOR EACH ROW 
BEGIN
    UPDATE post AS p
    SET p.total_likes = p.total_likes + 1
    WHERE p.pid = NEW.pid;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER userlikedcom AFTER INSERT ON commentlikes
FOR EACH ROW 
BEGIN
    UPDATE comment AS c
    SET c.total_likes = c.total_likes + 1
    WHERE c.cid = NEW.cid;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE decrementComLike(IN id INT)
BEGIN
	UPDATE comment AS c
    SET c.total_likes = c.total_likes - 1
    WHERE c.cid = id AND c.total_likes > 0;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER userdislikedcom
AFTER DELETE
ON commentlikes FOR EACH ROW
BEGIN
    CALL decrementComLike (OLD.cid);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE decrementLike(IN id INT)
BEGIN
	UPDATE post AS p
    SET p.total_likes = p.total_likes - 1
    WHERE p.pid = id AND p.total_likes > 0;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER userdisliked
AFTER DELETE
ON userlikes FOR EACH ROW
BEGIN
    CALL decrementLike (OLD.pid);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER user_auditTrail BEFORE UPDATE ON User
FOR EACH ROW
BEGIN
    INSERT INTO user_audit
    VALUES (CURRENT_DATE, CURRENT_USER, old.join_date, old.uid,
    old.username, old.total_posts, old.bio, new.bio);
END$$
DELIMITER ;

INSERT INTO User (username, password, bio) VALUES ('crazykatz', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'Hey! I have a bio');
INSERT INTO User (username, password, bio) VALUES ('pugluver', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'I made this account to post literally once');
INSERT INTO User (username, password, bio) VALUES ('trendsetter', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'uuhhhhh, look at the name :)');
INSERT INTO User (username, password, bio) VALUES ('tech_master', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'I really love to help people with their tech problems!');
INSERT INTO User (username, password, bio) VALUES ('computer_wiz', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'I accidentally know a thing or two about computers.');
INSERT INTO User (username, password, bio) VALUES ('techsmart', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'This is something of a bio');
INSERT INTO User (username, password, bio) VALUES ('fashionista', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'I literally only know clothes');
INSERT INTO User (username, password, bio) VALUES ('fabulouslyme', '$2y$10$J9zucgj9VqtIUDbHc73/AuZ7VsREdrcri8dxhyV/LBQdK3jcEbCGO', 'heyeyyyyy');

INSERT INTO Category(cname) values('Hardware');
INSERT INTO Category(cname) values('Performance');
INSERT INTO Category(cname) values('Software');

INSERT INTO followedusers values(1, 5);
INSERT INTO followedusers values(2, 5);
INSERT INTO followedusers values(5, 2);
INSERT INTO followedusers values(5, 4);
INSERT INTO followedusers values(4, 2);
INSERT INTO followedusers values(3, 1);
INSERT INTO followedusers values(3, 2);
INSERT INTO followedusers values(3, 4);
INSERT INTO followedusers values(3, 5);
INSERT INTO followedusers values(2, 1);

INSERT INTO Post(uid, catID, title, text) VALUES (7, 2, "How can you improve your computer's performance?", 'My computer is running out of ram and I can not figure it');
INSERT INTO Post(uid, catID, title, text) VALUES (1, 3, 'What is the best way to protect your computer from viruses?', 'I am scared that I am going to get hacked and I want to protect my computer');
INSERT INTO Post(uid, catID, title, text) VALUES (2, 3, 'What are some good free antivirus programs?', 'I need to get an antivirus, but I am not making a lot of money right now. Are there any good ones you can recommend?');
INSERT INTO Post(uid, catID, title, text) VALUES (1, 1, 'What are some good tips for backing up your data?', 'I have a lot of photos and I want to make sure that they are backed up in case my computer has any issues');
INSERT INTO Post(uid, catID, title, text) VALUES (3, 1, 'How can you troubleshoot a computer problem?', 'My computer keeps blue screening and I can not figure out why');
INSERT INTO Post(uid, catID, title, text) VALUES (4, 1, 'What are the best CPUs for gaming?', 'Im building a gaming computer pretty soon, and wondered what the best performance CPUs were available');
INSERT INTO Post(uid, catID, title, text) VALUES (5, 1, 'A good gpu for gaming on a budget', 'I am thinking about starting to build a gaming computer, and wondered if anyone had a suggestion for a gpu that will not break the bank');
INSERT INTO Post(uid, catID, title, text) VALUES (4, 1, 'What are the most common problems with computer building?', 'I am going to be building a computer pretty soon, and I was wondering if there are any pitfalls that I should avoid while actually building it');
INSERT INTO Post(uid, catID, title, text) VALUES (5, 1, 'What is the best way to cool my computer?', 'My computer keeps overheating and it is slowing the computer down. How can I keep the temperature down?');
INSERT INTO Post(uid, catID, title, text) VALUES (6, 3, 'What is the best software for video editing?', 'I am starting a youtube channel and I was wondering what the best video editing software to use would be.');

/*Comments and Likes for Post 1*/
INSERT INTO Comment(pid, text) VALUES(1, 'Have you tried downloading more RAM?');
INSERT INTO Comment(pid, text) VALUES(1, 'No, where do you do that?');
INSERT INTO Comment(pid, text) VALUES(1, 'downloadmoreram.com');
INSERT INTO Comment(pid, text) VALUES(1, 'Thanks, now my computer just doesnt work...');
INSERT INTO UserComments VALUES(2, 1);
INSERT INTO UserComments VALUES(7, 2);
INSERT INTO UserComments VALUES(2, 3);
INSERT INTO UserComments VALUES(7, 4);
INSERT INTO UserLikes VALUES(4, 1);
INSERT INTO UserLikes VALUES(2, 1); 
INSERT INTO CommentLikes VALUES (7, 2);
INSERT INTO CommentLikes VALUES (2, 1);

/*Comments and Likes for Post 2*/
INSERT INTO Comment(pid, text) VALUES(2, 'Use a reliable antivirus program and keep it up to date.');
INSERT INTO UserComments VALUES(8, 5);
INSERT INTO Comment(pid, text) VALUES(2, 'Avoid clicking on links in email messages or opening attachments from people you do not know.');
INSERT INTO UserComments VALUES(2, 6);
INSERT INTO Comment(pid, text) VALUES(2, 'Be careful what you download from the Internet. Make sure it comes from a reputable source.');
INSERT INTO UserComments VALUES(5, 7);
INSERT INTO Comment(pid, text) VALUES(2, 'Use a firewall to help block unauthorized access to your computer.');
INSERT INTO UserComments VALUES(3, 8);
INSERT INTO UserLikes VALUES(2, 2);
INSERT INTO CommentLikes VALUES(6, 5);
INSERT INTO CommentLikes VALUES(1, 5);
INSERT INTO CommentLikes VALUES(1, 6);


/*Comments and Likes for Post 3*/
INSERT INTO Comment(pid, text) VALUES(3, "I love Malwarebytes! It's free version is still really good!");
INSERT INTO UserComments VALUES(5, 9);
INSERT INTO Comment(pid, text) VALUES(3, "I like Avast because it's free and it's never given me any problems.");
INSERT INTO UserComments VALUES(7, 10);
INSERT INTO Comment(pid, text) VALUES(3, "I don't really trust free antivirus programs, so I always use a paid one.");
INSERT INTO UserComments VALUES(4, 11);
INSERT INTO Comment(pid, text) VALUES(3, "I don't really know much about antivirus programs, so I can't really comment on this.");
INSERT INTO UserComments VALUES(8, 12); 
INSERT INTO UserLikes VALUES(8, 3);
INSERT INTO UserLikes VALUES(5, 3);
INSERT INTO UserLikes VALUES(4, 3);
INSERT INTO CommentLikes VALUES(5, 10);
INSERT INTO CommentLikes VALUES(2, 10);
INSERT INTO CommentLikes VALUES(4, 12);



/*Comments and Likes for Post 4*/
INSERT INTO Comment(pid, text) VALUES(4, "Copy + Paste");
INSERT INTO UserComments VALUES(4, 13);
INSERT INTO Comment(pid, text) VALUES(4, "Store your backups in different locations to reduce the risk of losing everything if one location is damaged or destroyed.");
INSERT INTO UserComments VALUES(5, 14);
INSERT INTO Comment(pid, text) VALUES(4, "Always make sure to have at least two copies of your data in case one gets lost or corrupted.");
INSERT INTO UserComments VALUES(5, 15);
INSERT INTO Comment(pid, text) VALUES(4, "Also, there are some good apps for automatically backing up your data!");
INSERT INTO UserComments VALUES(5, 16);
INSERT INTO Comment(pid, text) VALUES(4, "THanks computer_wiz!!");
INSERT INTO UserComments VALUES(1, 17);
INSERT INTO UserLikes VALUES(3, 4);
INSERT INTO UserLikes VALUES(7, 4);
INSERT INTO CommentLikes VALUES(1, 14);
INSERT INTO CommentLikes VALUES(1, 15);
INSERT INTO CommentLikes VALUES(1, 16);



/*Comments and Likes for Post 5*/
INSERT INTO Comment(pid, text) VALUES(5, "A troubleshooting tip is to check for updates. Sometimes updates can fix problems with software.");
INSERT INTO UserComments VALUES(5, 18);
INSERT INTO Comment(pid, text) VALUES(5, "If all else fails, you can always take the computer to a professional to get it fixed.");
INSERT INTO UserComments VALUES(6, 19);
INSERT INTO Comment(pid, text) VALUES(5, "Just restart your pc dude");
INSERT INTO UserComments VALUES(4, 20);
INSERT INTO UserLikes VALUES(1, 5);
INSERT INTO UserLikes VALUES(4, 5);
INSERT INTO CommentLikes VALUES(3, 19);
INSERT INTO CommentLikes VALUES(4, 18);
INSERT INTO CommentLikes VALUES(1, 19);


/*Comments and Likes for Post 6*/
INSERT INTO Comment(pid, text) VALUES(6, "Best Overall - Ryzen 9 5900X, Best Budget - Ryzen 5 1600. Anything in between is still good :)");
INSERT INTO UserComments VALUES(5, 21);
INSERT INTO Comment(pid, text) VALUES(6, "Don't listen to him, go Intel.");
INSERT INTO UserComments VALUES(6, 22);
INSERT INTO UserLikes VALUES(2, 6);
INSERT INTO CommentLikes VALUES(2, 21);


/*Comments and Likes for Post 7*/
INSERT INTO Comment(pid, text) VALUES(7, "Best Budget? Probably the Nvidia 2060 Super. Other than that anything that came out after the Nvidia GTX 1060 is good :)");
INSERT INTO UserComments VALUES(6, 23);
INSERT INTO Comment(pid, text) VALUES(7, "Wow surprise surprise you go for nvidia, AMD is still good :/");
INSERT INTO UserComments VALUES(4, 24);
INSERT INTO UserLikes VALUES(1, 7);
INSERT INTO UserLikes VALUES(3, 7);
INSERT INTO UserLikes VALUES(6, 7);
INSERT INTO CommentLikes VALUES(8, 23);
INSERT INTO CommentLikes VALUES(7, 23);


/*Comments and Likes for Post 8*/
INSERT INTO Comment(pid, text) VALUES(8, "Honestly? Hardest thing is getting together Windows and drivers.");
INSERT INTO UserComments VALUES(5, 25);
INSERT INTO Comment(pid, text) VALUES(8, "One issue that can arise is poor cable management, its just hard...");
INSERT INTO UserComments VALUES(5, 26);
INSERT INTO Comment(pid, text) VALUES(8, "Also, literally anything can go wrong, and you don't have dell support on call for this one...");
INSERT INTO UserComments VALUES(5, 27);
INSERT INTO UserLikes VALUES(6, 8);
INSERT INTO CommentLikes VALUES(2, 25);
INSERT INTO CommentLikes VALUES(7, 26);
INSERT INTO CommentLikes VALUES(3, 26);


/*Comments and Likes for Post 9*/
INSERT INTO Comment(pid, text) VALUES(9, "Have you tried cold air? It's pretty helpful");
INSERT INTO UserComments VALUES(4, 28);
INSERT INTO Comment(pid, text) VALUES(9, "Buying more case fans will always help");
INSERT INTO UserComments VALUES(6, 29);
INSERT INTO Comment(pid, text) VALUES(9, "Ensure that your CPU cooler is mounted properly and that the thermal paste is applied correctly.");
INSERT INTO UserComments VALUES(4, 30);
INSERT INTO UserLikes VALUES(8, 9);
INSERT INTO UserLikes VALUES(2, 9);
INSERT INTO CommentLikes VALUES(1, 28);
INSERT INTO CommentLikes VALUES(8, 29);


/*Comments and Likes for Post 10*/
INSERT INTO Comment(pid, text) VALUES(10, "If you have a high end system, Premiere Pro is the way to go");
INSERT INTO UserComments VALUES(5, 31);
INSERT INTO Comment(pid, text) VALUES(10, "I highly recommend Sony Vegas Pro. It's very user friendly and has a lot of great features.");
INSERT INTO UserComments VALUES(3, 32);
INSERT INTO Comment(pid, text) VALUES(10, "Best free one is Davinci Resolve, hands down.");
INSERT INTO UserComments VALUES(4, 33);
INSERT INTO UserLikes VALUES(4, 10);
INSERT INTO CommentLikes VALUES(3, 33);
INSERT INTO CommentLikes VALUES(5, 33);

INSERT INTO Bookmarks VALUES(5, 1);
INSERT INTO Bookmarks VALUES(6, 1);
INSERT INTO Bookmarks VALUES(3, 2);
INSERT INTO Bookmarks VALUES(2, 2);
INSERT INTO Bookmarks VALUES(6, 2);
INSERT INTO Bookmarks VALUES(8, 3);
INSERT INTO Bookmarks VALUES(4, 4);
INSERT INTO Bookmarks VALUES(6, 4);
INSERT INTO Bookmarks VALUES(3, 6);
INSERT INTO Bookmarks VALUES(2, 6);
INSERT INTO Bookmarks VALUES(1, 6);
INSERT INTO Bookmarks VALUES(7, 7);
INSERT INTO Bookmarks VALUES(7, 8);
INSERT INTO Bookmarks VALUES(2, 8);
INSERT INTO Bookmarks VALUES(7, 9);
INSERT INTO Bookmarks VALUES(5, 10);
INSERT INTO Bookmarks VALUES(1, 10);

INSERT INTO CatLikes VALUES(5, 1);
INSERT INTO CatLikes VALUES(5, 2);
INSERT INTO CatLikes VALUES(5, 3);
INSERT INTO CatLikes VALUES(1, 1);
INSERT INTO CatLikes VALUES(4, 1);
INSERT INTO CatLikes VALUES(8, 2);
INSERT INTO CatLikes VALUES(2, 3);
INSERT INTO CatLikes VALUES(6, 1);