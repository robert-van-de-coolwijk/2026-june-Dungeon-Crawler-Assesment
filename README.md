# Dungeon Crawler

This repo is an assessment I am doing and to prove "I did all the work", I am putting all my GIT commits in here.

https://github.com/robert-van-de-coolwijk/2026-june-Dungeon-Crawler-Assesment/tree/master

## Quick Summary

- It's a PHP 8.4 text-based dungeon crawler (no framework).
- Features procedural world generation, rooms in a grid with compass navigation, creatures, basic combat, save/restore, and Docker (LAMPP stack).
- Main code is in the dungeon-crawler-project/ folder.
- It has a CLI + web (/public) interface.

## Installation

- Either install the LAMP / PHP web stack with included docker or provide your own.
- Composer.json will dictate if your environment works.

For the docker installation see the readme in
[docker-compose-lamp-master](docker-compose-lamp-master)

The game requires PHP 8.4

Copy the [dungeon-crawler-project](dungeon-crawler-project) folder into the web folder
- Then for web; expose for PHP the /public folder.
- Accss CLI through ./bin/run or ./bin/run.sh make sure chown flags are correct (executable).

When using the docker image the docker image, just copy it into the /www folder.

Warning: for the moment you have to run composer manually to run the game.

#### Not fully implemented yet:

For persistent worlds without (constant) disk reloads,
you need either; Redis, APC or a RAM disk.

Switching to these can be done in the config file.

## How to start (world creation)

The game is delivered "empty" and needs some setup.

When using the /public http pages

Browsing to world-creation.php will:
- make a player
- fill the world with rooms and portals (traverse method)
- fill the world with creatures

Browsing to text.php will:
- load game from disk
- look
- go: south, south, east, east, north
- Attempt to put a creature inside the current room.
- Keeps to continuously fight the creature


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

