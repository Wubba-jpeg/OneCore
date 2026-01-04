# OneCore
A simple gdps framework, currently only having support for gd 1.0 - 1.5

## supported features
>[!NOTE]
>This core is still being worked on,
>so some features may be buggy
- Uploading levels
- getting levels
- downloading levels
- liking levels
- leaderboards (other than creator lb)
- commenting
  
## Unsupported (planned) features
- difficulty filters

# Setup
## requirements
- A mysql/mariadb database
- PHP

## installing
- upload all files to your webserver
- edit config/connection.php
- upload database.sql to your db
- edit the links in your 1.0 apk
- edit the package name
- done!


# Special thanks/Credits
- Cvolton - the incl/lib/connection.php script (I just yoinked it straight from gmdprivateserver lol), and commenting is heavily referenced from their version of gmdprivateserver.
- Caster - testing the core

## license
gpl-3.0
