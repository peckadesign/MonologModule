# MonologModule

 - Vytváří `DayFileHandler`, který loguje výstup Monologu do struktury `log/kanál/YYYY-MM/YYYY-MM-DD.log`
 - Vytváří `BlueScreenHandler`, který ukládá výjimky z Tracy do `log/exception/YYYY-MM/`
 - Napojuje logování z Tracy do Monologu


## Instalace

```
$ composer require pd/monolog-module
```

## Nastavení

```
# common.neon

extensions:
	pd.monolog: \Pd\MonologModule\DI\Extension


pd.monolog:
	name: projekt


services:
	myService:
		arguments:
			logger: @\Pd\MonologModule\ChannelLoggerFactory::create('myChannel')
			
	-
		factory: \Monolog\Processor\WebProcessor

	-
		factory: \Pd\MonologModule\Handlers\DayFileHandler
		arguments:
			appName: myProjectName
			logDir: %logDir%

	-
		factory: \Pd\MonologModule\Handlers\BlueScreenProcessor
		arguments:
			logDir: %logDir%


	pd.monolog.logger:
		setup:
			- pushProcessor(@\Monolog\Processor\WebProcessor)
			- pushProcessor(@\Pd\MonologModule\Handlers\BlueScreenProcessor
			- pushHandler(@\Pd\MonologModule\Handlers\DayFileHandler)
			- pushHandler(@\Pd\CoreModule\LogModule\Handlers\NewRelicHandler)

```
