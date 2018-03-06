# Viewleaf Dashboard

This repo contains the source code of our TV Dashboard.

## Specification

- PHP/mySQL hosting that is compatible with Laravel 5.3
- Pusher Account
- Modern Web Browser
- 1080p TV

Our configured dashboard has following tiles:

- Meeting Room calendars (currently free/busy only) via [Google Calendar](https://google.com/calendar)
- Music currently playing on the Sonos
- Clock/date
- Latest 'announcement' from Slack. Currently set as the most recent post from either Tannoy or Cheshire with `@channel` or `@bigscreen` in the message
- Internet up/down via WebSockets
- Weather from DarkSky. Configured to the GPS co-ordinates for the Cheshire office

## Developer Docs

### Vagrant Box and Provisioning

The dashboard runs on the standard Homestead box provided by Laravel. Instructions for this can be found on the [Laravel Website](https://laravel.com/docs/5.3/homestead).
The site does require a mySQL database on the box called `viewleaf` so this will need to be added in the Homestead config file.

### Build Commands

Once the Homestead box is provisioned, SSH in and navigate to the `viewleaf` folder under the home directory. The Home stead box comes with Composer, Node and NPM out of the box so you can then install the application as follows.

#### Compass Dependancies
Installs Laravel and other API dependancies - `composer install`

#### Database Migrations and Seeding
Adds the tables and initial data required to run the dashboard - `php artisan migrate --seed`

#### NPM dependancy installation
Installs the front-end dependancies required to generate the CSS and Javascript - `npm install`

#### Gulp Tasks
Builds the CSS and Javascript  - `gulp`

#### CRON Tasks
Starts the Laravel Artisan Scheduled Task service. Add the following to the CRON (substituting the actual path to Artisan in the application) - `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
The actual tasks are in the Console/Kernel.php file to run every few minutes during office hours.

### Credentials and Config

All the sensitive credentials and API keys are stored in a .env file in the root of the project. This file is excluded from the repo and a template, .env.example, matches the fields in the populated copy.
If this file is lost, new credentials will have to be set up and the Sonos details will need to be rediscovered.

The first time you open the dashboard in a browser you will be asked for credentials. These are in [Confluence](https://blue-leaf.atlassian.net/wiki/spaces/BL/pages/106468387/Viewleaf+Dashboard)

### Overview of the Flow

1. All the services we monitor have scheduled tasks which send the responses to Events that get sent to Pusher
1. A Pusher subscription then broadcasts the data over a private WebSockets channel
1. The Javascript (Vue.js) side of the application is listening for the Events sent over WebSockets and updates the widgets accordingly

### Common Issues
RealVNC is installed on the Mac behind the TV so you can log in by installing the client on any machine and signing into the account detailed on [Confluence](https://blue-leaf.atlassian.net/wiki/spaces/BL/pages/93290500/RealVNC)

#### Wifi Drops out
Symptoms are the internet connection indicator goes red and it doesn't update any widget. When you exit fullscreen, the wifi icon in the toolbar will be faded out.

This one's annoying as you have to use the mouse and keyboard on the machine. Either reconnect or, if that doesn't work, `vagrant suspend` the box and reboot the Mac.

#### Vagrant box crashes
Symptoms are the internet connection indicator goes red and it doesn't update any widget. When you exit fullscreen, SSHing into the vagrant box takes for ever.

To fix it, run a `vagrant halt` then `vagrant up`. Trying `vagrant reload` will usually never complete while it waits for the box to respond. Running halt will force the shutdown if it doesn't respond in a timely manner.
