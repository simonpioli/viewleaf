# Viewleaf Dashboard

This repo contains the source code of our TV Dashboard.

## Specification

Our configured dashboard has following tiles:

- Meeting Room calendars (currently free/busy only) via [Google Calendar](https://google.com/calendar)
- Music currently playing on the Sonos
- Clock/date
- Latest 'announcement' from Slack. Currently set as the most recent post from either Tannoy or Cheshire with `@channel` or `@bigscreen` in the message
- Internet up/down via WebSockets

## Developer Docs

### Overview

1. APIs, scheduled tasks, events and Pusher setup
2. Vue.js setup with Pusher listener, events, data and templates

### Vagrant Box and Provisioning

Homestead setup file

### Credentials and Config

Requirements in the .env file and the various config files

### Build Commands

1. Compass dependancy installation
1. Database migrations and seeding
1. NPM dependancy installation
1. Gulp Tasks