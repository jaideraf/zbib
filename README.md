# zbib
A Z39.50 client for Wikincat

## Requirements
* [PHPYAZ](https://www.indexdata.com/resources/software/phpyaz/)
* PHP 7.4

## Installation
Clone this repository:

```Shell
git clone https://github.com/jaideraf/zbib.git
```

And rename the <code>targets.json</code> file according to <code>$targetsList</code> variable in the <code>Z3950ClientManager.php</code> file:

```Shell
cd zbib
mv targets.json.example targets.json
```

## Configuration
Configure <code>targets.json</code> objects as you wish. You can use the following properties: 
* <code>title</code>: the title of the Z39.50 target
* <code>zurl</code>: the host, port and database in the format "host:port/database"
* <code>userpass</code>: the user and password in the format "user/password"
* <code>charset</code>: the charset used in the retrieved records
* <code>syntax</code>: the syntax used in the retrieved records
* <code>flag</code>: a flag image to indicate the target country
* <code>default</code>: a boolean value to make the target already selected by default
