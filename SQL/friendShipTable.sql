DROP TABLE IF EXISTS userFriendships;
CREATE TABLE userFriendships (
	friendshipId INT NOT NULL AUTO_INCREMENT,
    user1Id INT NOT NULL,
    user2Id INT NOT NULL,
	requestPending ENUM('1','0') DEFAULT '1',
    establishDate TIMESTAMP NOT NULL,
    
    PRIMARY KEY (friendshipId),
    FOREIGN KEY (user1Id) REFERENCES users (userId), 
    FOREIGN KEY (user2Id) REFERENCES users(userId)
    
);