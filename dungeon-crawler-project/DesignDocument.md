# Core Principles

The idea is to allow total freedom.

When finally implemented is to have a hassle free create your own adventure game.

The world can be made entirely with individual commands.
Create room, create portal, create creature.

The template logic should be sufficient so you can quickly generate things for the world in a random way, but give you creative input.


## Notes on game generation

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

## Entities

Everything is an Entity: 
- All game objects inherit from Entity (with specializations like Room, Creature, Player, Portal, Container).
  - Identifiers: Every entity has a unique, auto-generated UniqueIdentifier ```id```.
  - Data Types (GameDataTypes): Strong typing and validation for properties:
    - ShortText / Text: ```Names```, ```descriptions```, etc.
    - Identifier: References to single entities. 
    - CollectionIdentifier: For many-to-one relations. 
    - Resource: Numeric values with max/current (e.g., health). 
    - Boolean, Attribute, etc.

## Containers & Nesting: 
Rooms and Creatures inherit from Container, so can be "inside of each other" like a Matryoshka doll.

Examples of possible valid options:
- Room  inside a room
- Room contains a container (chest for example) contains a creature (fox) with item money (gold)
- Two creatures and player inside a room

## World
Holds "registration / administration" over all entities. EVERY entity is registered inside the world.
References between items are just their ID. If relation is broken, it should be handled accordingly.

The game attempts it's very best to never let an item be:
- orphaned; a creature or item to not be inside a container
- lost; so it can not be reached by a creature
- dead linked: where a relation points to an item that is not inside the entity list

### Entity consistency and sane game state

Note the "the game attempts" (to keep the game sane and consistent) refers to player commands. 

In context of game save / restore all kinds of things can go wrong like entity files being corrupted, save failing between saving x number of entities. 

When restoring; the game logic will attempt to deliver a working world, but will not correct mistakes. 

Issues with the world are for admins or helper commands to fix. And admin commands do not adhere to any rules so an admin has the freedom to do remove, change or create at their hearts content.

The reason for this is simply that relations within a world setting can become complicated. A 6 entity deep container relationship with more than 8 portals involved and more than 20 items in various containers is nothing out of the ordinary.

Checking all of these as constrains will make:
- Every change a hassle
- Saving and restoring slow 

For very complicated code that will introduce more errors while working against the core principles of the game design philosophy to be boundless, endless and hassle free.
As every command that can possible manipulate an entity can create a breach inside a chain.

So lets keep it simple.

## Items
Represent "things" inside the game that can be picked up by creatures.

Only items can be in an inventory of a creature.

Properties
  - ```imovable``` Can only be picked up if true or player has the corrosponding key item
  - ```quantity``` Is 1 by default, can be more if ```quantifable = true```
  - ```quanifiable``` Things like gold, sand, liquids are a mass number of something.
  - ```quantity_prefix``` When printing, the value to show before the quantity 
  - ```quantity_postfix``` When printing, the value to show after the quantity

## Creatures
Are containers that can "move around" and be in a fight.

They have attributes like health. Specifically for health == 0, means the creature is death.

Creatures have a ``` home ``` property set, that is (also) their spawn location.

The idea is that a home can be set, after a creature is already in some room as a ways to recall them (toward) home.

## Journal
To track progress and happenings in the world. Any command that manipulates the game and causes "something to happen" will write a line in the players journal denoted as a turn.

## Game
Governs what actions are permitted by determining the game state. 

This state is dynamic and is determined by conditions like, player health and if they are in a room.

Here is a list:

### Creation / game setup

#### Blank
- Condition: When there is no player, there is nothing.
- Options: A player can be created.

#### Genesis
- Condition: One player exists, and is not inside a room
- Options: A world can be created, the player will be placed in a room upon creation. The world decides default start location, if note is given a empty room is chosen at default, if none available, any room will be chosen.

### Typical play states

#### Exploration
- Condition: The player is in a room,  health > 0
- Options: 
  - move (north, east, south, west)
  - look

#### Fight
- Condition: The player is in a room with creatures with health > 0
- Options: 
  - attack
  - flee (go back to last room)

#### GameOver
- Condition: The players health is zero
- Options: 
  - respawn (move player last room + reset health 100%)
  - abandon (delete player)
  - oblivion (delete world + player)

#### GameWon
- Condition: The victory condition has been met, entity player.won == true
- Options: 
  - continue (unset win condition on player)

The game can have varying win conditions set describing what the win condition is.

- False == The game can not be won, free roam, open world.
- True == Lock out all players as a means to do maintenance or end the world.
- Item ID == Must be in players inventory to win
- Room ID == Player must be in the room to win

A list of conditions separated with a comma, is permitted. All conditions must be met to win.

As a way of administration and simplicity. Any conditions are permitted, so adding false at the (beginning or) end of a list to keep players out while you are designing your world, is perfectly fine.

The player has the ``` won ``` property which is default false.

When the victory condition is met the won flag is set on the player and can be unset with the ``` continue ``` command.


# Command basics
For ease of use when there is no world the player can just create one. When the world gets populated restrictions will start applying.

A command consists of the command name itself followed by its parameters.

Commands can be restricted in that they are not available in certain contexts.

Params are command specific and various restrictions and requirements can apply.

## special parameters
For ease of you a couple of short hands will be available:

- ``` me ``` Points to the current player
- ``` home ``` Points to the spawn/home room of the player



# Administration
The game must be setup initially and might be expanded or build completely manually. Which both are done with the same command structure.


## Editing Commands (Super User)
This structure supports a powerful command system:

Creation:
- ``` create room [biome] [name] (optional)[Description) ```
- ``` create creature [type] [name]( optional)[Description) ```
- ``` create portal [sourceRoomId] [targetRoomId] [direction/name] ```

Modification:
- ``` set [entityId] name [name] ``` "New Name"
- ``` set [entityId] description [description] ``` "Long text here..."
- ``` set [creatureId] [attribute] [value] ``` An attribute change example ```set #22 health 12```

Relations / Movement:

- ``` link [roomA] [roomB] north ``` creates portal pair between rooms
- ``` put [item/creature] in [room/container] ``` as admin you can freely put any item or creature inside any container regardless of current location of any of them

Inspection:
- ``` inspect [id] ``` Gives a full verbose description of the given object regardless of location.
- ``` look [id] ``` Executes the look command, but will also show ID's of; itself, portals and contents. 
- ``` move [entityId] into [containerId]  ``` Will indiscriminately (regardless of current location) attempt to move items A into container B.
- ``` stats (optional)all ``` Gives the state of the world (entity counts, highest ID).

Authorization: Future checks like if player has admin flag or specific permissions.


# Gameplay
Relates to everything the player can do during normal gameplay.

## Commands

### Self
- ``` player ``` Will show all relevant information of the current player, like health and inventory.
- ``` me ``` Alias of ``` player ```
- ``` inventory ``` Will show the current inventory of the player
- ``` journal ``` Will show what happened to the player
- ``` diary ``` Same journal, but will attempt a more whimsical diary telling of what happened

### Senses
- ``` look ``` Examines the room the player is in.
- ``` look [name] ``` Examines the portal, creature or item with that name inside current room. If not hidden.

### Movement
- ``` walk [portal name]``` Will attempt to enter given portal.
- ``` go ``` alias of walk.

- Walk has the following shorthands; north, east, south and west

### Combat
When there is a live creature in the room, with the player, then you are in a fight.

Movement is restricted, instead:

- ``` flee ``` will attempt to reach the previous room.
- ``` fight ``` will attack a creature.

When there are multiple creatures, using command ``` fight ``` will list possible targets with a number.

Add the number after the fight command to specify who you want to attack.

### Containers & items
Items of various types will be available inside containers.

Treasure, equipment and weapons await.

To acquire them;

- ``` open [name] ``` Will show contents of container if not locked or hidden
- ``` take [name] ``` Will put the item from the room into player inventory if not immovable, locked or hidden.
- ``` take [name A] from [name B] ``` Will take item A from inside container B if not locked or hidden.
- ``` put [name] ``` Will put item from player inventory into current room.
- ``` put [name A] into [name B] ``` Will put item A inside of container B.

## Item and container properties

To keep things interesting, various entities like doors (portal), chests (container) and treasure (items) can be restricted.

### hidden
To not show items, creatures, containers or portals until you have "the item in your possession to reveal them".
 - True == can not be seen. 
 - ID == Entity with ID must be in player inventory to see. 
 - False == visible (default).

### lock 
Locks so there can be a challenge to taking the contents of a container or prevent entry before a key is found.
- True == container can not be opened / portal can not be used. 
- ID == Entity with ID must be in player inventory to use or open. 
- False == Container can be opened / portal can be moved through (default)

### immovable 
Immovable options as (bolted down) furniture prevents the player from putting the whole world in their inventory.

- True == can not be taken (default). 
- ID == Entity with ID must be in player inventory to take. 
- False == can be taken.
