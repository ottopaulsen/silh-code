# silh-code
SIL Håndball Nettsted Plugin


## TO DO Otto

Skriv de viktigste kommandoene her.


## Git

[Git-bok](https://git-scm.com/book/en/v2)


### Komme i gang

* [Installer git](#### Installering av git) kommandolinjeverktøy
* Opprett bruker på [github](https://github.com/)
* Sett opp [SSH mot github](https://help.github.com/articles/generating-ssh-keys/)

#### Installering av git

Det kan gjøres på flere måter, og det kan hende du må ha en spesiell versjon i forhold til OS-et. Eksempel:

`sudo apt-get -y install git`

Konfigurering av git:
```
git config --global user.name "Ditt Fornavn Og Etternavn"
git config --global user.email "din.epost@adresse.no"
```

### De viktigste kommandoene

Hent ned koden første gang med `git clone https://github.com/ottopaulsen/silh-code`

Se hva som er endret, m.m. med `git status`

Legg til nye filer med `git add <filnavn>`

Commit endringer med en av disse
```
git commit
git commit -a
git commit -am"Kommentarer" 
```
Bruk gjerne en av de to første, og skriv fyldige kommentarer. Ta en titt på github hvordan kommentarene vises. Første linje er en slags overskrift.

Før du laster opp endringer til github, merge inn eventuelle oppdaterte filer derfra med `git pull origin master` eller bare `git pull`.

Last opp dine endringer med `git push`

Når det som ligger på master skal settes i produksjon, kjør:

```
git checkout Prod
git merge master
git push origin Prod
```
Her er sikkert en del rutiner som må finpusses etter hvert...


## Test-server

Først må du sende webmaster din public key (f.eks. `~/.ssh/id_rsa.pub`), og få den lagt inn i `~/.ssh/authorized_keys` på serveren.

Test-serveren ligger på AWS. IP-nummer og brukernavn får du av webmaster. Skriv IP-nummeret i browseren for å teste nettsidene. Bruk `ssh -i ~/.ssh/id_rsa <brukernavn>@<ip-nummer>` for å logge inn på serveren. 

Plugin-filene på test-serveren ligger på `/var/www/html/wp-content/plugins/silh-code`. Du kan f.eks. legge de ut dit med kommandoen `scp -i ~/.ssh/id_rsa <filnavn> <brukernavn>@<ip-adresse>:/var/www/html/wp-content/plugins/silh-code/php/`. Dette er for alle php-filene utenom silh-code.php. Den ligger ett nivå kenger opp.

Dette kan automatiseres på en bedre måte...






