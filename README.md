# MonologModule

 - Vytváří `DayFileHandler`, který loguje výstup Monologu do struktury `log/kanál/YYYY-MM/YYYY-MM-DD.log`
 - Umožňuje zobrazit v presenteru všechen výstup Monologu prostřednictvím FlashMessage. Vhodné např. pro administraci, kdy se zobrazí všechen výstup ze synchroních operací prováděných při požadavku obsluhy (e-maily, výstupy importů/exportů, atd.)

## Instalace

```
$ composer require pd/monolog-module
```

## Nastavení

```
# common.neon

extensions:
	pd.monolog: Pd\MonologModule\DI\Extension


monolog:
	name: projekt
	handlers:
		- Pd\MonologModule\Handlers\DayFileHandler("projekt", %logDir%)


pd.monolog:
	# Povolené typy prosenterů pro zobrazení výstupu Monologu jako FlashMessage
	allowedTypes:
		- Pd\AdminModule\BasePresenter
```
