Exec { path => [ "/bin/", "/sbin/" , "/usr/bin/", "/usr/sbin/" ] }

class basics {
    exec { 'apt-get update': }

    $sysPackages = [ "build-essential" ]
    package { $sysPackages:
        ensure => "installed",
    }

    # Basic packages needed.
    $packages = ["make", "vim", "curl", "git", "ack-grep"]
    package { $packages:
        ensure => 'installed'
    }
}

class app {
    class { "nginx": }

    class { 'php':
        service => 'nginx',
    }

    # PHP Modules
    $phpModules = [
        "imagick",
        "intl",
        "memcache",
        "mcrypt",
        "xdebug",
    ]

    php::module { $phpModules: }
    php::module { 'apc':
        module_prefix => 'php-',
    }
}

Exec["apt-get update"] -> Package <| |>

include basics
include app
