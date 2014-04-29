SecurityKB Dokuwiki
===================

Instalation
---
**Important Security Note:** When storing sensitive data in this fork of Dokuwiki, make sure that the webroot is not accessible by other systems on the same network. This can be done by telling your web server to only use a local interface or by setting firewall rules. Additional security can be added by setting the ACL Policy setting to 'Closed Wiki'. If you want to be even more careful, add a .htaccess file to prevent access from untrusted source IPs.

### Using Truecrypt (Optional)
There are a lot of reasons Dokuwiki was chosen to be the basis for the framework, but one of the biggest considerations was Dokuwiki's ability to work without a database. This allows a tester to store all the engagement notes, screenshots, and loot in a Truecrypt volume. To create a Truecrypt volume just run the following (from a Linux box).
```bash
truecrypt -t -c yymmdd-engagement_id.tc
```
Follow the Truecrypt wizard and use whatever options you want, but make sure the filesystem will work with the web server you are using (if you aren't sure, use Linux Ext4). Once the Truecrypt volume has been created, just mount it to your web root (or a folder in your web root) like so:
```bash
truecrypt -t yymmdd-engagement_id.tc /var/local_webroot/current
```

### Setting Up the Wiki
Change into the webroot directory (or a folder in the webroot directory) and then run:
```bash
git clone https://github.com/securitykb/securitykb_dokuwiki.git .
```
*(Note the extra '.' at the end. That tells git not to clone everything into a new folder.)*

You may need to change the file and folder permissions so the web server can view everything. Checkout the [official documentation][1] for more details 

**Pro Tip:** If you want to customize the template to fit your needs, you can clone the repo to a folder, make the changes, and then compress the folder (e.g. tgz, 7z, zip). You can then pass this out as your own custom tempalte or keep it on file for future engagements. If you have anything the community could use, please submit a pull request.

Added Goodies
---
This version of Dokuwiki has a few additional plugins added:
* [color]
* [hidden]
* [pagelist]
* [pageredirect]
* [sortablejs]
* [tag]
* [timestamp]
* [todo]

The template was just a few slight modifications to the [Adora Dark Template]



Contributing
---
It is easy to contribute to the SecurityKB Pentest Notes framework:
* Make a fork of the SecurityKB Dokuwiki Repository
* Clone your repo to a local system for editing (e.g. git clone https://github.com/<your name>/securitykb_dokuwiki)
* Make changes
* Use git to push your changes back to Github (e.g. git push)
* Open a pull request
* For more information on using git with Github check out http://try.github.io
* If you want to get involved with the DokuWiki community check out the [DokuWiki Newsletter] and the [DokuWiki User Forum].


License
---
This fork of Dokuwiki adheres to the same license as Dokuwiki and the added plugins and template. See COPYING and plugin folders for more details.



Original Dokuwiki Help
---
All documentation for DokuWiki is available online at http://www.dokuwiki.org/

For Installation Instructions see http://www.dokuwiki.org/install

DokuWiki - 2004-2013 (c) Andreas Gohr <andi@splitbrain.org> and the DokuWiki Community

See COPYING and file headers for license info


[Adora Dark Template]: https://www.dokuwiki.org/template:adoradark
[Dokuwiki Newsletter]: https://www.dokuwiki.org/newsletter
[DokuWiki User Forum]: https://forum.dokuwiki.org/
[color]: https://www.dokuwiki.org/plugin:color
[hidden]: https://www.dokuwiki.org/plugin:hidden
[pagelist]: https://www.dokuwiki.org/plugin:pagelist
[pageredirect]: https://www.dokuwiki.org/plugin:pageredirect
[sortablejs]: https://www.dokuwiki.org/plugin:sortablejs
[tag]: https://www.dokuwiki.org/plugin:tag
[timestamp]: https://www.dokuwiki.org/plugin:timestamp
[todo]: https://www.dokuwiki.org/plugin:todo
[1]: https://www.dokuwiki.org/install:permissions
