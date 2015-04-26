ha5kdr-dmr-live
===============

Wordpress plugin which displays a searchable, live amateur radio Hytera DMR network log table.
Data is from http://ham-dmr.de/live_dmr

Downloader & updater scripts also included (notice: they should not be run from the www-data folder!)

#### Usage

- Edit and rename **ha5kdr-dmr-live-config-example.inc.php** to **ha5kdr-dmr-live-config.inc.php**,
**ha5kdr-dmr-live-example.css** to **ha5kdr-dmr-live.css**.
- Enable the plugin on the Wordpress plugin configuration page.
- Add **ha5kdr-dmr-live-update-check.sh** to crontab and make it run every minute. This will start one
  instance of the **ha5kdr-dmr-live-update.sh** script which runs **ha5kdr-dmr-live-process.php** every
  5 seconds, so the database will be updated every 5 seconds.
- Copy **loader-example.gif** to **loader.gif**.
- [Download and extract a flag pack](http://www.free-country-flags.com/) and set it's URL in the config.

To show the live log, insert this to a Wordpress page or post:

```
<ha5kdr-dmr-live />
```

You can see a working example [here](http://www.ha5kdr.hu/projektek/dmr/status).
