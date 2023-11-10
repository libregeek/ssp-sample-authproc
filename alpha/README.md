# Sample SimpleSAMLphp Authentication Process Filters
Copy the alpha find beta folders to the SimpleSAMLphp/modules and enable it using the following config

Configure the modules in config/config.php
```
5 => array(
	'class' => 'alpha:Alpha',
	//'%precondition' => 'return false;',
),
6 => array(
	'class' => 'beta:Beta',
	//'%precondition' => 'return false;',
),
```

Enable the module in config/config.php
```
'module.enable' => [
	'alpha' => true,
	'beta' => true
],
```
