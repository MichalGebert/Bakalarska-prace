# Automatizované testování webové aplikace GoodAccess
## Struktura
```
├───tests - složka s testy
│   ├───_data - složka pro data - např. sql soubor pro reset databáze
│   │ 
│   ├───_output - logování neúspěčných testů (např. html stránka, screen obrazovky, ...)
│   │ 
│   ├───_support - pomocné soubory
│   │   ├───Helper - vlastní pomocné příkazy
│   │   ├───Page - Page Objecty
│   │   │   └───AcceptanceChrome
│   │   │       ├───Account
│   │   │       └───ControlPanel
│   │   ├───Step - Step Objecty
│   │   └───_generated - pomocné Codeception třídy
│   │
│   ├───acceptanceChrome - akceptační testy pomocí WebDriveru
│   │   ├───Account - složka s testy na Account modulu
│   │   └───ControlPanel - složka s testy na ControlPanel modulu
│   │
│   ├───acceptancePhpBrowser - akceptační testy pomocí PhpBrowseru     
│   │ 
│   ├───integration - integrační testy
│   │   ├───facade
│   │   │   ├───ControlPanel     
│   │   │   │   └───Account      
│   │   │   └───GoodAccess
│   │   ├───model
│   │   │   └───factory
│   │   │       ├───organization
│   │   │       └───Token
│   │   └───services
│   │       └───payment
│   │
│   ├───unit - unit testy
│   │   └───App
│   │       ├───facade
│   │       │   ├───GoodAccess
│   │       │   └───PartnerPanel
│   │       └───Helpers
│   │
│   ├───acceptanceChrome.suite.yml - konfigurační soubor pro testy pomocí WebDriveru
│   ├───acceptancePhpBrowser.suite.yml - konfigurační soubor pro testy pomocí PhpBrowseru
│   ├───integration.suite.yml - konfigurační soubor pro integrační testy
│   └───unit.suite.yml - konfigurační soubor pro unit testy
│   
├───videa
│   ├───Akceptační testy.mp4 - spuštění akceptačních testů pomocí Selenium WebDriveru za užití Chrome prohlížeče (skript automaticky "proklikává" a kontroluje webovou aplikaci GoodAccess)
│   ├───GitLab CI pipeline.mp4 - názorné vytvoření merge requestu, spuštění pipeline, procházení jednotlivých fázích, ukázka artefaktů, ukázka výsledku testů
│   ├───Integrační testy.mp4 - spuštění integračních testů
│   ├───Neúspěšný test - informace o chybě do aplikace Slack.mp4 - po neůspěšném testu přijde notifikace s informací o chybě do aplikace Slack
│   ├───PHPCodeSniffer.mp4 - analýza kódu pomocí PHPCodeSnifferu a následná automatická oprava chyb
│   ├───PHPStan.mp4 - spuštění statické analýzy pomocí PHPStan 
│   └─── Unit testy.mp4 - spuštění unit testů
│
├───.gitlab-ci.yml - configurační soubor pro GitLab pipeline
├───codeception.yml - configurační soubor pro Codeception
└───phpstan.neon - configurační soubor pro PHPStan
