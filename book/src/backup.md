# Backups

> [!WARNING]
> **Always test backup restoration procedures on a non-critical environment before applying to production.**  
> Incorrect restoration can lead to data loss or service disruption.

| Category       | Severity   | Downtime |
| -------------  |:----------:| ------------:|
| :wrench: Maintenance | Critical | 15 minutes  |

### Introduction

UNIT3D includes backup tooling that can create backups and manage the routine. This guide outlines the backups dashboard, the configuration file handling backups, and the process of creating and restoring a UNIT3D backup, including decryption with the `APP_KEY`, file restoration, permission management, and cache‑reset procedures.


> [!NOTE]
> Note: While incredibly handy, it is not recommended to use the built-in backup routine for sites above **[! ENTER A RECOMMENDED THRESHOLD]** as backups might incur timeouts or if greater features, such as deduplication, are required.
> 

### Built-In Backups

Built‑in backups, located in `.../storage/backups/UNT3D`, offer an efficient way to migrate a development codebase to production by leveraging these backups directly. The administrator simply selects one of the most recent backups, copies it to a temporary directory, and retrieves the site master key from the `.env` file (the `APP_KEY`). Next, the backup is uncompressed with `p7zip`—prompting for the key—and any additional ZIP files (typically containing files and a database) are extracted. Finally, the files are restored to the server and the database is imported manually, ensuring the server runs only the committed code.

#### Managing the built-in backup routine

The built‑in backups dashboard link can be found in the Staff dashboard menu or by navigating to `yourSite.tld/dashboard/backups`. This front‑end management screen provides a quick view of the status, health, size, and quantity of backups. Across the top bar are quick options—`Create Full Backup`, `Create Database Backup`, and `Create Files Backup`—that allow administrators to run unscheduled backups without logging in to the server. Using this dashboard helps staff verify at a glance whether the options set in the configuration meet the necessary requirements.

The configuration file that manages the built‑in backup routine is located at `.../config/backup.php`; administrators can modify this to suit specific needs.

- `Name`:  The name of this application. You can use this name to monitor the backups.
- `Source`: Set up as an associative array. Within you'll find a 'files' key with child keys detailed below.
- `Include`: The list of directories and files that will be included in the backup.
- `Exclude`: These directories and files will be excluded from the backup. Directories used by the backup process will automatically be excluded.
- `Follow__links`: Determines if symlinks should be followed.
- `Ignore_unreadable_directories`: Determines if it should avoid unreadable directories.
- `Relative_path`: This path is used to make directories in resulting zip-file relative. Set to `null` to include complete absolute path
  - e.g. `base_path()`
- `Databases`: The names of the connections to the databases that should be backed up MySQL, PostgreSQL, SQLite and Mongo databases are supported.
  - The content of the database dump may be customized for each connection by adding a 'dump' key to the connection settings in `.../config/database.php`.
    
    ```php
        'mysql' => [
               ...
              'dump' => [
                   'excludeTables' => [
                        'table_to_exclude_from_backup',
                        'another_table_to_exclude'
                    ]
              ],
          ],
    ```

  - If you are using only InnoDB tables on a MySQL server, you can also supply the useSingleTransaction option to avoid table locking.
    
    ```php
        'mysql' => [
               ...
             'dump' => [
                  'useSingleTransaction' => true,
              ],
        ],
    ```
       
    For a complete list of available customization options, see: https://github.com/spatie/db-dumper
  - `Database_dump_compressor`: The database dump can be compressed to decrease disk space usage. If you do not want any compressor at all, set it to null.
      - Out of the box Laravel-backup supplies: `Spatie\DbDumper\Compressors\GzipCompressor::class`
      - You can also create custom compressor. More info on that here: https://github.com/spatie/db-dumper#using-compression

- `Destination`: Options to modify settings of destination archive/locations
  - `Filename_prefix`: The filename prefix used for the backup zip file.
  - `Disks`: The disk names on which the backups will be stored.

- `Temporary_directory`: The directory where the temporary files will be stored.

- `Notifications`: You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     - For Slack you need to install laravel/slack-notification-channel.
     - You can also use your own notification classes, just make sure the class is named after one of the `Spatie\Backup\Events` classes.

- `Notifiable`: Here you can specify the notifiable to which the notifications should be sent. The default notifiable will use the variables specified in this config file.
  - The default settings include built-in support for both mail and slack notifications.

- `Monitor_backups`: Here you can specify which backups should be monitored. If a backup does not meet the specified requirements the `UnHealthyBackupWasFound` event will be fired. Here is where you can set both standard time period between backups and the maximum amount of storage to use before considering unhealthy.

- `Cleanup`: The strategy that will be used to cleanup old backups. The default strategy  will keep all backups for a certain amount of days. After that period only a daily backup will be kept. After that period only weekly backups will be kept and so on. No matter how you configure it the default strategy will never delete the newest backup.
  - `Keep_all_backups_for_days`: The number of days for which backups must be kept.
  - `Keep_daily_backups_for_days`: The number of days for which daily backups must be kept.
  - `Keep_weekly_backups_for_weeks`: The number of weeks for which one weekly backup must be kept.
  - `Keep_monthly_backups_for_months`: The number of months for which one monthly backup must be kept.
  - `Keep_yearly_backups_for_years`: The number of years for which one yearly backup must be kept.
  - `Delete_oldest_backups_when_using_more_megabytes_than`: After cleaning up the backups remove the oldest backup until this amount of megabytes has been reached.

- `Security`: This option sets the encryption properties for the outer archive of your backups.
  - `Password`: Set by default to use the `APP_KEY` variable from the `.env` file.
  - `Encryption`: Set by default to use the encryption helper.

---

### Create a Backup
To run a backup of a UNIT3D installation, navigate to the project directory and execute the Artisan backup command:


```sh
cd /var/www/html
php artisan backup:run
```

### Viewing Backup List

To view available backups, run:

```sh
cd /var/www/html
php artisan backup:list
```

---

## Wrap-Up

- **Install Required Tools:** Set up 7-Zip and other utilities.
- **Retrieve Application Key:** Extract the `APP_KEY` from the `.env` file.
- **Uncompress the Backup:** Use 7-Zip to decrypt and extract the UNIT3D backup files.
- **Restore Files:** Copy the restored files to a live environment using proper commands.
- **Fix File Permissions:** Ensure correct ownership and permissions with `chown`, `chmod`, and related commands.
- **Reset Caches:** Clear caches and restart PHP Artisan and PHP-FPM to finalize restoration.


---

## Index

- [Prerequisites](#prerequisites)
- [1. Install Required Tools](#1-install-required-tools)
- [2. Retrieve the Application Key](#2-retrieve-the-application-key)
- [3. Uncompress the Backup](#3-uncompress-the-backup)
- [4. Restore the Files](#4-restore-the-files)
- [5. Fix File Permissions](#5-fix-file-permissions)
- [6. Reset PHP Artisan and PHP-FPM](#6-reset-php-artisan-and-php-fpm)
- [Troubleshooting](#troubleshooting)

---

## Prerequisites

- **User Privileges:** Sudo access is required for installing packages and changing file permissions.
- **Tools:**  
  - [7-Zip](https://www.7-zip.org/) (for extracting encrypted backups and handling ZIP files)  
  - A text editor (e.g., `nano` or `micro`)
- **Backup File:** A UNIT3D backup file (for example: `[UNIT3D]2025-03-05-00-00-51.zip`) stored in the UNIT3D backup directory.
- **App Key:** The `APP_KEY` from the `.env` file, which is used as the decryption password when extracting the backup.

> **Note:** This tutorial was tested on **UNIT3D v9.0.5** running on **PHP 8.4**. If using a different version, some commands and steps may require adjustments.


---

## 1. Install Required Tools

First, ensure the tools needed to extract the backup are in place by using `apt` to update and install `p7zip-full`.

```sh
cd ~
sudo apt update
sudo apt install p7zip-full -y
```

`p7zip-full` is used for handling compatibility and encryption issues when uncompressing the backup archives.


---

## 2. Retrieve the Application Key
The backup file is encrypted with the `APP_KEY`. Open the `.env` file to locate it.

```sh
sudo nano /var/www/html/.env
```

Copy the `APP_KEY` value (look for line starting with `APP_KEY=`)

or

Retrieve key from environment:

```sh
sudo grep 'APP_KEY=' /var/www/html/.env
```

This key will be needed when uncompressing the backup.

---

## 3. Uncompress the Backup

### 3.1 Create a Temporary Directory
Create a temporary directory to work in and navigate into it:

> [!NOTE]
> Note: This guide uses the user's home directory, as this is generally safe. Administrators should choose the location that best suits the host machine. 
> 

```sh
mkdir ~/tempBackup
cd ~/tempBackup
```

### 3.2 Copy the Backup File
Copy the backup file from its location (e.g., `.../storage/backups/UNT3D`) to the temporary directory. Adjust the file name and path as needed:

```sh
cp /var/www/html/storage/backups/UNIT3D/\[UNIT3D\]2025-03-22-17-29-59.zip ./
```

Tip: Instead of typing the full file name, copy and paste the directory path (e.g., `.../backups/UNIT3D/\[UNIT3D`), then press the Tab key to auto-complete the backup file name:

```sh
cp /var/www/html/storage/backups/UNIT3D/\[UNIT3D
```
> [!NOTE]
> Note: Remember to add ./ at the end of the command to specify the current directory as the destination.
> 
For example: `cp /var/www/html/storage/backups/UNIT3D/\[UNIT3D\]2025-03-22-17-29-59.zip ./`



### 3.3 Extract the Backup File Using 7z
Use `7z` to extract the encrypted backup file. When prompted, enter the `APP_KEY`. The password prompt will not echo any input:

```sh
7z x \[UNIT3D\]2025-03-22-17-29-59.zip
```

#### Example: APP_KEY Usage
If the `.env` file contains:

```sh
APP_KEY=base64:bT70DC4Ck7taYqP6ugqKIYbAbiEFbgECSdc03MwtXg=
```

When prompted during extraction, enter the password (note: any input will not be echoed):

```sh
base64:bT70DC4Ck7taYqP6ugqKIYbAbiEFbgECSdc03MwtXg=
```

### 3.4 Unzip the Extracted Archive
If the extraction process produces a standard ZIP file (e.g., `backup.zip`), extract it with:

```sh
7z x backup.zip
```

---

**Explanation:**  
- The first `7z` command decrypts and extracts the outer backup archive using the `APP_KEY`.  
- The second `7z` command handles the inner ZIP file to further extract the backup contents.


---

## 4. Restore the Files
After extraction, backup files will be available in the temporary directory. Now, copy them back to the web server directory.

Copy the Entire `html` Directory:

```sh
sudo cp -rv ~/tempBackup/var/www/html /var/www
```

Alternatively, depending on the setup, the administrator might need to copy at a higher level:

```sh
sudo cp -rv ~/tempBackup/var/www /var
```

Or Restore Specific Files:

```sh
sudo -u www-data cp -rf ./var/www/html/someDirectory/someFile.php /var/www/html/someDirectory/someFile.php
```

Tip:
The administrator should choose the command that best fits the restoration needs. If only specific files need to be restored, only those files should be copied.

---


## Manual Backup Method

---
> [!NOTE]
> Be careful, the steps below are a manual method for creating backups (not using UNIT3D tooling) and restoring them! Only continue if you understand what you are doing.


---

If a temporary backup directory has not been created, run:

```sh
mkdir ~/tempBackup
```


### 1 Create a Zip Archive of the `www` directory
First, create a zipped archive of the entire `/var/www` directory and save it to the temporary backup directory:

```sh
sudo 7za a -tzip -r ~/tempBackup/www_backup_$(date +%Y%m%d%H%M%S).zip /var/www
```

This command compresses the entire `/var/www` directory into a zip file and saves it in the temporary backup directory (`~/tempBackup`).

The file will be named with a timestamp for reference. For example: `www_backup_20250322062714.zip`

### 2 Unzip the Archive into a New Restoration Directory

Create a new directory (e.g., `restore_www_TIMESTAMP`) in the temporary backup directory and unzip the archive into it:

The directory should be named following the format `restore_www_TIMESTAMP`, where `TIMESTAMP` corresponds to the timestamp in the backup file.

> [!NOTE]
> **Important:** Replace `$(date +%Y%m%d%H%M%S)` with the actual timestamp from the backup file.
> 
> For example, if the backup file is named `www_backup_20250322062714.zip`, then set `TIMESTAMP=20250322062714`.


```sh
TIMESTAMP=$(date +%Y%m%d%H%M%S)
mkdir ~/tempBackup/restore_www_$TIMESTAMP
7z x ~/tempBackup/www_backup_$TIMESTAMP.zip -d ~/tempBackup/restore_www_$TIMESTAMP
```

> The administrator must ensure that the same timestamp is used for both creating and unzipping the archive. Alternatively, a consistent name can be specified manually.

### 3 Copy the Restored Files to the Correct Location
Once the restored files in the new directory have been verified, copy the entire contents back to the live directory:

```sh
TIMESTAMP=$(date +%Y%m%d%H%M%S)
sudo cp -a ~/tempBackup/restore_www_$TIMESTAMP/var/www/html/. /var/www/html/
```

_The -a flag preserves file permissions, ownership, and timestamps._


> [!NOTE]
> **Restoration Command Template**
> ```sh
> sudo cp -a ~/tempBackup/restore_www_$TIMESTAMP/var/www/html/. /var/www/html/
> ```
> 
> **Key Parameters** (adjust accordingly):
> - `$TIMESTAMP`: Timestamp from the backup filename (e.g., `20250323064937`).
> The directory `/var/www/html/` is used as the default project directory in this documentation.
>
> If an administrator wants to back up a different directory, simply replace `/var/www/html/` with the path to the desired directory in all  the commands.
> 
> **Example for /html restoration**:
> ```sh
> sudo cp -a ~/tempBackup/restore_www_20250323064937/var/www/html/. /var/www/html/
> ```
> 
> Before applying any commands, double-check that the backup file contains the correct file structure (e.g., `/var/www/html/` in the backup ZIP archive) and that the live directory (e.g., `target-dir/`) matches the intended deployment path.
> 
> **Tip**: Preview structure first:
> ```sh
> 7z -l www_backup_$TIMESTAMP.zip | grep "var/www/html"
> ```




---

## 5. Fix File Permissions
Once the files are restored, adjust the file permissions to ensure the web server can access them correctly.

```sh
cd /var/www/html
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type f -exec chmod 664 {} \;
sudo find /var/www/html -type d -exec chmod 775 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Explanation:

_chown changes the owner to www-data, which is typically the web server user.
chmod commands set the proper permissions: files are set to 664 and directories to 775.
The additional commands ensure that the storage and bootstrap/cache directories have the correct group and permissions for writing._

### 5.1 Optional: Reinstall Dependencies and Rebuild Assets

If reinstalling Node.js dependencies and running a build process is part of the backup recovery, then execute the command below.

The administrator should run this only if a valid node_modules directory is absent or if the assets require rebuilding.

Note: Running this command with sudo may cause permission issues (such as EACCES errors) so if possible consider running it as a non-root user or ensure that the permissions are correctly set after installation.

When to Use:
Use the optional command if it is necessary to ensure that dependencies and built assets are updated.

> [!NOTE]
> If the backup already contains a valid node_modules directory with pre-built assets, this step can be skipped to avoid potential permission issues.

Optional Rebuild Command:

```sh
sudo rm -rf node_modules && sudo bun install && sudo bun run build
```

`sudo rm -rf node_modules`: Removes the current Node.js dependencies to allow a fresh install.
`sudo bun install`: Reinstalls all dependencies as specified in the project’s configuration.
`sudo bun run build`: Runs the build process to compile or bundle assets as needed.

---

## 6. Reset PHP Artisan and PHP-FPM
Finally, reset caches and queues with PHP Artisan and restart the PHP-FPM service.

```sh
cd /var/www/html
sudo php artisan set:all_cache && sudo systemctl restart php8.4-fpm && sudo php artisan queue:restart
```

Explanation:

_`php artisan set:all_cache` clears and rebuilds the application's cache.  
`systemctl restart php8.4-fpm` restarts PHP-FPM; ensuring that any changes are recognized by the PHP process.  
`php artisan queue:restart` restarts the queue; allowing any queued jobs to continue without issues._


## Troubleshooting

Below are some common issues and their suggested solutions:

| **Symptom**                | **Solution**                                              |
|----------------------------|-----------------------------------------------------------|
| 500 Internal Server Error  | Run `php artisan optimize:clear`                          |
| Database Connection Issues | Verify the `.env` credentials                            |
| Missing Files              | Re-run `rsync` with the `--checksum` option               |
| Permission Denied          | Reapply ACL permissions                                   |
| Queue Workers Inactive     | Restart all workers with `sudo supervisorctl restart all` |





