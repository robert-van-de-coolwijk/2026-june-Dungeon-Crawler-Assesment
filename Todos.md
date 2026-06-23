Todo's
V- Make rooms connected
V- Create player command

V- Drop the player in a room

V- Make the world persistent by saving it on disk
V-- And reloading it on starting the game

V- Create "monsters" and populate them at random in the rooms
V- Make a fight mechanic

- Implement proper game states
  - Explorer
  - Fight
  - Game over

- Implement Redis class
    - Make game load from memory on game entry, gracefully

- Create treasure items
  - Populate them in rooms at random
  - Make it so the player can pick them up

- Implement journal;
The player has traversed the game and fought monsters, 
but it might all go by quickly if you just cheese the game.
This will solidify the idea "you did something and it matters".
  - Journal command to show current entries
  - Make it so actions register an appropriate message in the journal
  - On game over, either prompt the player or just open the journal

- Fix composer file
	- run application from new context to test
	- include auto fixer that checks for vender folder and runs composer on 



[ Archived 12 june 2026
# PC voorbereiden
V- Installeer PHP storm
V- DB editor / IDE
V- Installeer een LAMP stack
V-- Docker installeren
V-- Docker compose file maken
V-- Docker containers uitrollen
V-- PHP met web server
V--- Composer instaleren
-- MariaDB (testen)
- Standaard applicatie testen in elkaar draaien (hello world)

# Project voorbereiding
V- Kijken of het geheel met docker of cubenets kan worden beheerd
- Composer installeren

V- Git project maken en code + alle toebehoren committen

V- Eerste opzet met CLI
V-- Project met CLI en MVC gecopieerd van bestaand project
V-- Alles vermaken en testen zodat er een "lege applicatie" klaar staat om mee te gaan bouwen

- Opzet met een HTML output (optioneel)

- Project document doorkammen
-- Todo's aanvullen
-- Alle open toedo's verplaatsen naar een "betere plek":
V--- OF dedicated MD document structuur in het project
--- OF Open project

# Project opzet wensen
- Frontend; Vue.js?
- Pure PHP!

# Implementatie

## Model
> Kijken naar Entity Component System pattern
Sources
>> https://en.wikipedia.org/wiki/Entity_component_system
>> https://github.com/SanderMertens/ecs-faq
>> https://austinmorlan.com/posts/entity_component_system/
- Data structuur met polyformisme
V-- Entity
V--- Container
V--- Room
-- Interactions

- Tokenizer voor commando's

## World generation
> Kijk naar Wavefunction collapse (Townscaper is voorbeeld game)
> Kijk naar procedural generation