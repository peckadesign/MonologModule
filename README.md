# MonologModule

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
