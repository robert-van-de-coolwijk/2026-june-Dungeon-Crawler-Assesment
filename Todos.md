Todo's
V- Make rooms connected
V- Create player command

V- Drop the player in a room

V- Make the world persistent by saving it on disk
V-- And reloading it on starting the game

V- Create "monsters" and populate them at random in the rooms
V- Make a fight mechanic

- Implement proper game states
V  - Explorer
V  - Fight
  - Game over
  - Game won
    - Implement win conditions on game
      - Optionally with combinations classes and / or.
      - Optional: Per player based reversable win condition:
        - Player.Won will track if the win conditions have been met for current player
        - Player.WinCount number of times the player won the game
        - Implement continue command to set Player.Won = false and need a way to remove whatever made the player win

- Implement Redis class
    - Make game load from memory on game entry, gracefully
      - On main check;
        - Config cache restore on init
        - Config cache mode is valid
        - See if there is a cache key

- Create treasure items
  - Populate them in rooms at random
  - Make it so the player can pick them up

- Implement journal;
The player has traversed the game and fought monsters, 
but it might all go by quickly if you just cheese the game.
This will solidify the idea "you did something and it matters".
  - Make it so actions register an appropriate message in the journal
  - Implement journal command; to show current entries
  - On game over, either prompt the player or just open the journal

- Fix composer file
	- run application from new context to test
	- include auto fixer that checks for vendor folder and runs composer when missing (look online there is a default mechanism for this)

- Consider building "basic" database cache / store logic for more flexibility
- consider introducing JIT cache loading, that only requests an entity from cache when it is requested to reduce memory footprint of PHP instance.


[ Archived 12 june 2026 ]
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