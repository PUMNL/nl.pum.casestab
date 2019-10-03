# nl.pum.casestab

This CiviCRM-extension adds a new cases tab to a contact card which shows all cases instead of the default cases tab which shows a limit of 20 cases only.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

![Screenshot](https://raw.github.com/PUMNL/nl.pum.casestab/master/images/screenshot.png)

## Requirements

* PHP 5.6 (Tested, might work with other version, but not tested)
* CiviCRM 4.4.8 (Tested, might work with other version, but not tested)

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl nl.pum.casestab@https://github.com/PUMNL/nl.pum.casestab/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/PUMNL/nl.pum.casestab.git
cv en casestab
```

## Usage

After installation you will see a new 'Cases PUM' tab on the contact card.
This tab does almost the same thing as the normal tab, but it shows al cases instead of the limited number in the original tab.
On a certain amount of cases it shows a pager to prevent lots of data load when there are many cases.