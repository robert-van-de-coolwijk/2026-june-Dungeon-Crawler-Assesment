# Dungeon Crawler

This repo is an assessment I am doing and to prove "I did all the work", I am putting all my GIT commits in here.

https://github.com/robert-van-de-coolwijk/2026-june-Dungeon-Crawler-Assesment/tree/master

## Installation

Either install your LAMP / PHP web stack through included docker or provide your own.
Composer.json will dictate if your environment works.

For the docker installation see the readme in
[docker-compose-lamp-master](docker-compose-lamp-master)

The game requires PHP 8.4

Then you have to copy the [dungeon-crawler-project](dungeon-crawler-project) folder into the web folder.

With the docker image, just copy it into the /www folder.

#### Not fully implemented yet:

For persistent worlds without (constant) disk reloads,
you need either; Redis, APC or a RAM disk.

Switching to these can be done in the config file.

## How to start (world creation)

The game is delivered "empty" and needs some setup.

When using the /public http pages, run world-creation.php will:
- make a player
- fill the world with rooms and portals (traverse method)
- fill the world with creatures

The same can be achieved with CLI in continues mode.

Use the following commands:

`player [player name]` to name your player character
- Example: player Dantilus

`init world [a .txt file put in the init folder]` to create rooms
- Example: init world world_1.txt

`init creatures [a .json file with creatures] [number of creatures]` to create creatures
- Example: creatures random_monsters.json 100


## How to play

This game  features rooms arranged in a grid you can traverse through the world using compass directions.

Type `north`, `east`, `south` or `west`

Use the `look`, to have a look at the current room.

When there is a creature in the room use `fight` to attack them.
With multiple creature, the `fight` command will prompt you to select which monster you want to attack.

The creatures will fight back as soon as you attack them.

They can kill you and you can kill them.

## administration 

`save` lets you save the game state (player, world, creatures, etc) to disk

`restore` loads the game from disk

`time` show current server time

## Missing features
- The game can not technically be won, there no end tile yet.
- The game can not fully be lost. Getting killed doesn't stop you from moving.
- Items are implemented, but commands and a bunch of game logic is missing.
- There is no scoring system.

## Notes on game generation

The idea is to allow total freedom.

When finally implemented is to have a hassle free create your own adventure game.

The world can be made entirely with individual commands.
Create room, create portal, create creature.

The template logic should be sufficient so you can quickly generate things for the world in a random way, but give you creative input.

By only putting in letters you can build the grid of rooms in this world,
which will be connected with the default compass portals.

Every letter corresponds with a biome:
- F for forest
- M for mountain

This biome is used to open the connected .json file and at random select a preset from that file.

However, the game doesn't dictate this is the only way.
A room can have as many portals as you like.

A room is "a container" meaning, you can put a room, into a room.

You can: have a clearing, with 4 adjacent rooms, containing a house, that has 3 floors and like 9 rooms in total that lets you leave the house, through a tunnel in the cellar into another room.

Preperations have been made to include a lock system, so you have to find "keys" to enter certain rooms.

