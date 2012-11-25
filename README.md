# Asset combo loader server

A basic server that combo-loads assets. It stands on the shoulders of giants such as the Symfony Components and Assetic asset management.

## Example

/combo?mylib/myscript.js&mylib/myother.js

## HttpCache for everyone, even on a shared host.

Combo Loader uses Symfony2 Reverse Proxy by default (written in PHP). This works out of the box, and it is really simple to add Varnish or Squid in front of the combo loader for better performance.