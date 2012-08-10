#ChatBoard#

A simple PHP/JavaScript site dedicated to anonymous chat and file sharing over a LAN.


##About##

Initially I was just playing around with some code, but after reading about the [PirateBox](http://wiki.daviddarts.com/PirateBox) project (and seeing as I recently bought a [Raspberry Pi](http://www.raspberrypi.org/)) I decided I wanted that kind of system - but one that has all the features I need and none of the features I don't, whilst being super-easy to maintain, extend, customise and use.

Until I have decided on a license to use for this project, here are a few basics:

1. Issue submission and minor bug fixes are appreciated, if not encouraged.
2. Anyone can use, modify and re-distribute this project, but doing so requires acknowledging me as the original author.
3. Regardless of the state of this project at any time and regardless of any implications you may see anywhere in the universe: **ABSOLUTELY NO WARRANTY OF ANY KIND IS PROVIDED WITH THIS PROJECT - I CLAIM NO FITNESS FOR ANY GIVEN PURPOSE SO _USE AT YOUR OWN RISK!!!_**


##Hopes & Dreams##

* A place to chat and share files anonymously on a LAN
* As quick as possible
* Ease of use & maintenance > small size & obfuscation
* Only HTML, PHP, JavaScript and CSS (_libraries/frameworks are fine though_)
* Choice between flat-file and MySQL storage
* Open plugin architecture for customisation
* Free, customisable and open-source (any help on choosing a license would be appreciated)
* Easily switch between archiving and deleting messages after an arbitrary time limit
  * Archived messages would be available to all users
* Initial 'official' plugins to include:
  * Private messages (follows global archiving settings)
  * Private file transfers (not being permanently stored on server)
  * File manager (instead of an upload page and a directory listing)


##Reality##

* Register name to your IP address (file on server)
* Send global messages
  * Plain text
  * Basic BBCode formatting
  * Images from URL with optional resizing
* Messages archived every day
  * Archived messages on server but not visible to users
* Not object-oriented