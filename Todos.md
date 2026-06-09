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
--- OF dedicated MD document structuur in het project
--- OF Open project

# Project opzet wensen
- Gebruik maken van Vue.js
- Pure PHP

# Implementatie

## Model
> Kijken naar Entity Component System pattern
Sources
>> https://en.wikipedia.org/wiki/Entity_component_system
>> https://github.com/SanderMertens/ecs-faq
>> https://austinmorlan.com/posts/entity_component_system/
- Data structuur met polyformisme
-- Entity
--- Container
--- Room (decorator)
-- Interactions

- Tokenizer voor commando's

## World generation
> Kijk naar Wavefunction collapse (Townscaper is voorbeeld game)
> Kijk naar procedural generation