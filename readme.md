# Spustenie projektu

- composer install
- npm install
- npm run build


# Požiadavky

- PHP 8.2
- NPM
- Ext v súbore composer.json


# Autorská poznámka

> Nebol som si úplne istý ako chcete aby výsledok vyzeral a preto som na to išiel trošku inak ako ste možno zvyknutý. Možno som šiel úplne mimo mísu ale naozaj to bolo pomerne strohé zadanie bez nejakých konkrétnejších špecifikácii. 
> Snažil som sa to napísať čo najviac rozšíriteľne. S časových dôvodov som sa nestihol vymazliť s FE kedže vo firme nám všetko horí po sezóne tak snaď vám to bude stačiť.

## XML problematika

Fiktívny problém s XML som vyriešil následovne. Napísal som si abstraktný BaseXmlRepository z ktorého dedí EmployerRepository. Teda celý BaseXmlRepository je v podstate vrstva ktorá spravuje XML súbor ako "databázu". Je to naozaj primitívna metóda bez nejakých joinov a where clausuli ktoré by však nebol problém doplniť. Výhoda v tomto prístupe je že je možnosť vytvoriť x počet tabuliek a dokážem si predstaviť aj FK medzi nimi (aj keď to by robil len blázon :D). 

Výhoda v tomto riešení je že v prípade rozšírenia o dalšiu "tabuľku" stačí vytvoriť len repozitár a entitu k nemu. To je možné automatizovať cez nette/php-generator v nejakom development commande.

Je možné celú túto problematiku vyriešiť aj jednoduchým XML konvertovaním do array a pracovať nad ním ale myslel som si že by to nebolo dostatočné a práve v tom bol ten catch od vás.

Možno by šlo napísať krajšie aby XML zvládol pracovať s entitou ale to by bol kanón na mravca.

## Štruktúra employer

> Opäť sa ospravedlňujem nebol som si istý zadaním. Nevedel som si predstaviť čo znamená "najjednoduchšie rozšíriteľná o ďalšie atribúty".

Dátovu štrukturu som vyriešil cez DTO. S týmto riešením som prišiel najmä kvôli striktným dátovým typom, napovedaniu v IDE a näjmä možnosti držať ako hodnotu ENUM, DATETIME e.t.c. Tento problém išiel samozrejme vyriešiť aj iným spôsobom a to vytvoriť CRUD na štruktúru tabuľky ktorá by sa držala v neone prípadne dalšiom XML súbore a celé to mohol validovať nette/scheme. Ale keď už som k tomu pristupil ako k databáze tak mi prišlo nelogické aby uživateľ mohol upravovať štruktúru tabuľky. Takže stačí pridať atribút do objektu employera a už si len prispôsobiť datagrid, formuláre atd. Maximálne 5 riadkov kódu.

## CRUD

Ako datagrid som použil knižnicu od contributte contributte/datagrid. Samozrejme som mohol použiť aj nejaký JS datagrid kde by som si cez API posielal json s dátami ale toto mi prišlo dostačujúce. Ako source dát som použil array aj keď by nebolo zložité vytvoriť triedu ktorá by implementovala IDataSource a pracovala priamo s polom entít prípadne priamo s XML.
Formuláre su klasika nette a všetky metody ktoré som použil na read, edit, delete sú v EmployerRepository. Viem o tom že formulár by išlo znovu použiť ale som zvyknutý na celkom odlišené a pomerne zložité procesy za update oproti create tak z praxe to píšem takto. 

## Graf

Na graf som použil Chart.js kde som mu len predal dáta ktoré som konvertoval do jsonu. Teda žiadna zložitá veda. 

