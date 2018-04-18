#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: card
#------------------------------------------------------------

CREATE TABLE card(
        card_id              int (11) Auto_increment  NOT NULL ,
        card_name            Varchar (25) NOT NULL ,
        card_description     Varchar (90) NOT NULL ,
        card_bg              Varchar (25) NOT NULL ,
        card_mana            Int NOT NULL ,
        card_life            Int NOT NULL ,
        card_attack          Int NOT NULL ,
        card_damage_received Int NOT NULL ,
        card_status          Varchar (25) ,
        type_id              Int NOT NULL ,
        deck_id              Int NOT NULL ,
        PRIMARY KEY (card_id ) ,
        UNIQUE (card_name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: type
#------------------------------------------------------------

CREATE TABLE type(
        type_id   int (11) Auto_increment  NOT NULL ,
        type_name Varchar (25) NOT NULL ,
        type_bg   Varchar (25) NOT NULL ,
        PRIMARY KEY (type_id ) ,
        UNIQUE (type_name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE user(
        user_id          int (11) Auto_increment  NOT NULL ,
        user_last_name   Varchar (25) ,
        user_first_name  Varchar (25) ,
        user_mail        Varchar (25) NOT NULL ,
        user_login       Varchar (25) NOT NULL ,
        user_password    Varchar (25) NOT NULL ,
        user_avatar      Varchar (50) ,
        user_inscription Datetime NOT NULL ,
        user_winned_game Int ,
        user_played_game Int ,
        user_ready       Int NOT NULL ,
        deck_id          Int NOT NULL ,
        role_id          Int NOT NULL ,
        game_id          Int NOT NULL ,
        tmp_hero_id      Int NOT NULL ,
        PRIMARY KEY (user_id ) ,
        UNIQUE (user_mail ,user_login )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: deck
#------------------------------------------------------------

CREATE TABLE deck(
        deck_id         int (11) Auto_increment  NOT NULL ,
        deck_name       Varchar (25) NOT NULL ,
        deck_color      Varchar (25) NOT NULL ,
        hero_name       Varchar (25) ,
        hero_bg         Varchar (25) ,
        hero_mana       Int ,
        hero_life       Int ,
        hero_degat_recu Int ,
        PRIMARY KEY (deck_id ) ,
        UNIQUE (deck_name ,deck_color ,hero_name ,hero_bg )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: role
#------------------------------------------------------------

CREATE TABLE role(
        role_id    int (11) Auto_increment  NOT NULL ,
        role_name  Varchar (25) NOT NULL ,
        role_power Int NOT NULL ,
        PRIMARY KEY (role_id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: action
#------------------------------------------------------------

CREATE TABLE action(
        action_id   int (11) Auto_increment  NOT NULL ,
        action_name Varchar (25) NOT NULL ,
        PRIMARY KEY (action_id ) ,
        UNIQUE (action_name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: newsletter
#------------------------------------------------------------

CREATE TABLE newsletter(
        suscriber_id          int (11) Auto_increment  NOT NULL ,
        suscriber_mail        Varchar (25) NOT NULL ,
        suscriber_inscription Datetime NOT NULL ,
        suscriber_status      Int NOT NULL ,
        PRIMARY KEY (suscriber_id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: game
#------------------------------------------------------------

CREATE TABLE game(
        game_id          int (11) Auto_increment  NOT NULL ,
        game_player_1_id Int ,
        game_player_2_id Int ,
        game_turn        Int ,
        PRIMARY KEY (game_id ) ,
        UNIQUE (game_player_1_id ,game_player_2_id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: tmp_card
#------------------------------------------------------------

CREATE TABLE tmp_card(
        tmp_card_id              int (11) Auto_increment  NOT NULL ,
        tmp_card_status          Varchar (11) NOT NULL ,
        tmp_card_damage_received Int NOT NULL ,
        user_id                  Int NOT NULL ,
        PRIMARY KEY (tmp_card_id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: tmp_hero
#------------------------------------------------------------

CREATE TABLE tmp_hero(
        tmp_hero_id              int (11) Auto_increment  NOT NULL ,
        tmp_hero_mana            Int NOT NULL ,
        tmp_hero_damage_received Int NOT NULL ,
        user_id                  Int NOT NULL ,
        PRIMARY KEY (tmp_hero_id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: can
#------------------------------------------------------------

CREATE TABLE can(
        role_id   Int NOT NULL ,
        action_id Int NOT NULL ,
        PRIMARY KEY (role_id ,action_id )
)ENGINE=InnoDB;

ALTER TABLE card ADD CONSTRAINT FK_card_type_id FOREIGN KEY (type_id) REFERENCES type(type_id);
ALTER TABLE card ADD CONSTRAINT FK_card_deck_id FOREIGN KEY (deck_id) REFERENCES deck(deck_id);
ALTER TABLE user ADD CONSTRAINT FK_user_deck_id FOREIGN KEY (deck_id) REFERENCES deck(deck_id);
ALTER TABLE user ADD CONSTRAINT FK_user_role_id FOREIGN KEY (role_id) REFERENCES role(role_id);
ALTER TABLE user ADD CONSTRAINT FK_user_game_id FOREIGN KEY (game_id) REFERENCES game(game_id);
ALTER TABLE user ADD CONSTRAINT FK_user_tmp_hero_id FOREIGN KEY (tmp_hero_id) REFERENCES tmp_hero(tmp_hero_id);
ALTER TABLE tmp_card ADD CONSTRAINT FK_tmp_card_user_id FOREIGN KEY (user_id) REFERENCES user(user_id);
ALTER TABLE tmp_hero ADD CONSTRAINT FK_tmp_hero_user_id FOREIGN KEY (user_id) REFERENCES user(user_id);
ALTER TABLE can ADD CONSTRAINT FK_can_role_id FOREIGN KEY (role_id) REFERENCES role(role_id);
ALTER TABLE can ADD CONSTRAINT FK_can_action_id FOREIGN KEY (action_id) REFERENCES action(action_id);
