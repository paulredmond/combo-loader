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

    nginx::vhost { 'combo-loader.dev' :
        template => 'app/nginx-vhost.erb',
        docroot  => '/vagrant/web',
    }

    package { 'php5-fpm':
        ensure => $ensure,
    }

    service { 'php5-fpm':
        ensure  => 'running',
        require => Package['nginx'],
    }


    file { '/etc/php5/cli/conf.d/xdebug.ini':
        ensure => 'present',
        path => '/etc/php5/cli/conf.d/xdebug.ini',
        content => template('app/xdebug.ini.erb'),
        notify => [ Service['php5-fpm'], Service['nginx'] ]
    }

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
