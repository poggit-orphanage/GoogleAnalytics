
#Google Analytics for PocketMine
###By DarkN3ss
This plugin will allow you to track events:

* Connections (With IP)
* Player Logins/spawns
* Quits (and time spent on the server)
* Respawns
* Deaths (and who/what/where)

As well as the ton of other stuff you can track/do with Google Analytics, like track what country the player comes from.

How did I come up with this idea? Well Fox32 over on the bukkit website made one for bukkit
with the bukkit API in Java [Link Here](http://dev.bukkit.org/bukkit-plugins/googleanalyticsplugin/), So I thought why not have one for PocketMine in PHP,
that way I can keep track of my servers without even needing to connect to them,
as well as being able to access my live stats on the go!

##Install

* Setup a Google Analytics Account [HERE](http://www.google.com/analytics/)
* Setup a Profile for your Server o n Google Analytics(Admin>Under account click "Create new account">enter details and save)
* Put the plugin file in your plugin folder
* Run the plugin once to generate the default config file of the plugin
* Setup the configuration file as described under this sentence

analyticsServerDomain: example.com (or 'MyServer')

The domain you chose for your google analytics report without the protocol ("http://") or empty if you didn't choosed a domain.
You can leave it empty if you don't now what to insert.

analyticsServerAccount: UA-XXXXXXXX-X or MO-XXXXXXXX-X The tracking id of your google analytics report

VIDEO of Google Analytics Setup: https://www.youtube.com/watch?v=NdszM2srY6U

by DarkN3ss

