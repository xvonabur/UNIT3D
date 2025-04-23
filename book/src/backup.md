# Backups

UNIT3D offers built in backup tools, available through the web dashboard or via Artisan commands, allowing you to create, manage, and restore your application snapshots.

> [!IMPORTANT]  
> Not recommended for large sites or when you need deduplication or distributed storage, as it may cause timeouts and performance issues.

## 1. Configuration

 **Customize** `config/backup.php` in your editor and adjust settings as needed; inline notes outline the available configuration parameters.

 **Key structure**:  

- **`backup`**  

    - **`name`**   

    - **`source`**   
        - **`files`**  

            Specifies which directories and files to `include` and which to `exclude` in the backup.

            ```php
                'include' => [
                    base_path(),           
                 ],

                'exclude' => [
                    base_path(),
                    base_path('vendor'),
                    base_path('node_modules'),
                    base_path('storage'),
                    base_path('public/vendor/joypixels'), 
                 ],
            ```       

           - **`follow_links`** 

           - **`ignore_unreadable_directories`** 

           - **`relative_path`** 

        - **`databases`**  

            Specifies the database connections to back up.

    - **`database_dump_compressor`**  

         Compressor class (e.g. `Spatie\DbDumper\Compressors\GzipCompressor::class`) or `null` to disable.

    - **`destination`**  

         Defines the storage location for backup files.​

    - **`temporary_directory`**  

     Staging directory for temporary files.

- **`notifications`**  

    Define when and how backup events trigger alerts via mail, Slack, or custom channels. 

- **`monitor_backups`**  

    Detect backup issues; triggers `UnhealthyBackupWasFound` when needed.  

- **`cleanup`**  

    Define how long to keep backups and when to purge old archives. 

    - **`strategy`**  

    - **`default_strategy`**  

         Keeps all backups for 7 days; then retains daily backups for 16 days, weekly for 8 weeks, monthly for 4 months, and yearly for 2 years. Deletes old backups exceeding 5000 MB.

         ```php
             'keep_all_backups_for_days'                => 7,
             'keep_daily_backups_for_days'              => 16,
             'keep_weekly_backups_for_weeks'            => 8,
             'keep_monthly_backups_for_months'          => 4,
             'keep_yearly_backups_for_years'            => 2,
             'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
         ```

- **`security`**  

    Ensure that only someone with your `APP_KEY` can decrypt and restore snapshots.

## 2. Create a Backup

**Note:** You can access the built-in Backups dashboard from the Staff menu. It shows each backup’s status, health, size, and count, and lets administrators launch unscheduled full, database, or files-only backups instantly. Another approach is to use the command line.

- **Run** the Artisan command:

   ```sh
   php artisan backup:run
   ```

   Creates a timestamped ZIP containing files and database dumps.


## 3. Viewing Backup List

- **List** existing backups:

   ```sh
   php artisan backup:list
   ```


## 4. Restoring a Backup

> [!WARNING]  
> **Always test backup restoration procedures on a non‑critical environment before applying to production.**  
> Incorrect restoration can lead to data loss or service disruption.

1. **Install prerequisites** (Debian/Ubuntu):

   ```sh
   sudo apt update && sudo apt install p7zip-full -y
   ```

2. **Retrieve** your application key:

   ```sh
   grep '^APP_KEY=' /var/www/html/.env
   ```

3. **Extract** the outer archive (enter APP_KEY when prompted):

   ```sh
   7z x [UNIT3D]YYYY-MM-DD-HH-MM-SS.zip
   ```

4. **Unzip** inner archive, if generated:

   ```sh
   7z x backup.zip
   ```

5. **Copy** restored files to webroot:

   ```sh
   sudo cp -a ~/tempBackup/var/www/html/. /var/www/html/
   ```

6. **Fix** file permissions:

   ```sh
   sudo chown -R www-data:www-data /var/www/html
   sudo find /var/www/html -type f -exec chmod 664 {} \;
   sudo find /var/www/html -type d -exec chmod 775 {} \;
   ```

## 5. Reset & Cleanup

> After restoring backups, ensure to reset configurations and clean up temporary files to maintain system integrity.

1. **Fix** permissions:

   ```sh
   sudo chown -R www-data:www-data /var/www/html
   sudo find /var/www/html -type f -exec chmod 664 {} \;
   sudo find /var/www/html -type d -exec chmod 775 {} \;
   ```

2. **Reinstall** Node dependencies & rebuild (if assets need it):

   ```sh
   sudo rm -rf node_modules && sudo bun install && sudo bun run build
   ```

3. **Clear** caches & restart services:

   ```sh
   cd /var/www/html
   sudo php artisan set:all_cache
   sudo systemctl restart php8.4-fpm
   sudo php artisan queue:restart
   ```

## 6. Optional Manual Backup Method

> [!NOTE]
> Use only as a fallback.

1. **Create ZIP** of entire `/var/www`:

   ```sh
   sudo 7za a -tzip -r ~/tempBackup/www_backup_$(date +%Y%m%d%H%M%S).zip /var/www
   ```

2. **Unzip** into a restore directory:

   ```sh
   TIMESTAMP=20250322062714
   mkdir ~/tempBackup/restore_www_$TIMESTAMP
   7z x ~/tempBackup/www_backup_$TIMESTAMP.zip -d ~/tempBackup/restore_www_$TIMESTAMP
   ```

3. **Copy** back to live path:

   ```sh
   sudo cp -a ~/tempBackup/restore_www_$TIMESTAMP/var/www/html/. /var/www/html/
   ```   
