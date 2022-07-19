# Task List
- [x] Allow Administrators to dynamically alter/change the intervals' variable
- [x] Retrieve JSON data from the user's PC via the proctoring script
- [x] Decode the JSON data from the user's PC (BASE64 UTF-16LE) [For UUID]
- [x] Decrypt the decoded JSON data with Fernet Key [For Raw Data]
- [x] Allow Administrators to process/view the information retrieved
- [x] Store proctoring script on Web Server for the RaspberryPi to access and run
- [ ] Generate Asymmetric Key Pair (RSA) to encrypt the all the data during transmission in between the user's PC and the Web Server

---

# Web Server
Currently hosted on Hostinger for testing purposes.
Alternatively, you may host on your own environment or utilize XAMPP to do so.
Download XAMPP: https://www.apachefriends.org/

## Prequisites
1. Web Hosting Environment
2. PHP Library (7.0 and above)
3. SQL Server (Maria DB, MySQL or any other suitable SQL Server)
4. Fernet (PHP `hash` Extension and `openssl` or `mcrypt` Extension)
   - Reference: https://github.com/kelvinmo/fernet-php
   - Used to decrypt the JSON data sent from the user's PC (AFTER LAYER 2 ENCRYPTION)
5. Bootstrap (5.2)
   - Used to beautify the admin panel
6. DataTables
   - Reference: https://datatables.net/
   - Utilized for easy sorting of the data stored on the SQL Server

## Features/Files
```
index.php
```
- Displays the decrypted and decoded JSON data stored inside database
&nbsp;

```
nav_bar.php
```
- Navigation Bar used for all the pages for easy maneuvering
&nbsp;

```
upload.php, upload_process.php
```
- For administrator to upload the proctoring script onto the Web Server.
- Only allows `ps1` as the file type and `proctoring_script.ps1` as the file name
- This feature should not be implemented in live production. It is purely for convenience purposes during testing only.
- The proctoring script will be stored in the `uploads` folder. The `uploads` folder has to be created beforehand for this feature to work.
&nbsp;

```
process.php, process_list.php
```
- The proctoring script on the user's PC will be sending JSON data to either `process.php` or `process_list.php` depending on whether it contains any list. `process.php` is used to process JSON data without lists while `process_list.php` is used to process JSON data with lists.
- The JSON data contains the Fernet Key to decrypt the rest of the data as well as the UUID encoded in BASE64(UTF-16LE) format
- The JSON data will be decoded and decrypted before being processed.
- Default interval values of 300 seconds will be initialized on first instance of receiving the JSON data for a unique UUID.
- Interval values will be shortened automatically when triggered, provided that administrator(s) does not override the interval values. (E.g. User's PC is running suspicious softwares)
- The decoded and decrypted JSON data will then be stored on SQL Server for analysis or viewing purposes.
&nbsp;

```
Fernet.php
```
- Fernet: A symmetric encryption that encrypts data with a generated key.
- Reference: https://github.com/kelvinmo/fernet-php
- The JSON data will be encrypted with Fernet before it is being sent over to the Web Server, alongside with the key for the Web Server to decrypt and process the data.
&nbsp;

```
interval.php
```
- For the proctoring script to continually poll and retrieve the interval values for the various categories via GET parameters encoded with BASE64(UTF-16LE)
- Example: `https://hostingserver.com/interval.php?uuid=MQAyADMANAA1ADYANwA4ADkA&category=QQBXAEQA`
&nbsp;

```
admin_interval.php, admin_interval_delete.php, admin_interval_delete_process.php, admin_interval_edit.php, admin_interval_edit_process.php
```
- For the administrator(s) to edit or delete the interval values for the various categories.
- When the parameter `admin_override` is `true` or `1`, the proctoring script will no longer be able to automatically change the interval values
&nbsp;

```
admin_uuidlist.php, admin_uuidlist_delete.php, admin_uuidlist_delete_process.php
```
- For the administrator(s) to view the unique UUIDs and delete all data pertaining to a specified UUID for housekeeping purposes.
&nbsp;

## Test Run
Run the following commands in PowerShell after the PowerShell Execution Policy has been set to `unrestricted` or `remotesigned`
This JSON data contains a list (The fourth element) and hence, we will forward the JSON data to `process_list.php` instead
```
$data = '{  
    "1":  "gAAAAABi1E07uUtQTaJsuNIDn08eYJc1uHGoi_iZHXL0lSEROQdNeBNzL0N_wvUkoQQkDWIWJJA02X5Z2Yr1z1GynAWk3DWQfg==",  
    "2":  "gAAAAABi1E07ALHUuvYZDpJCUTG-LkQIEqnyd2KyISNCikwESbrY57x-9FhbJfKkIex13I7_2nc2b8pAj0e-6u8k5kxe1fllPw==",  
    "3":  "gAAAAABi1E07ft-cKXlWWz4VFy4Pnekm-rc4s35EAhElcJDQA5mUtGFhb6lP8zTx8Q5K9ZTq5UhT0gFm2uaa0GtMuLL3qw6GRg==",  
    "4":  ["gAAAAABi1E07tO8CS9pd0SFf8tD9bhDh9VZGdQkMT7uAoEUhNlxwzG40vnYJ6ta7bpiGBbNWGqCcyGlAG0-_8obDWktWooA02Q==",  
    "gAAAAABi1E07tO8CS9pd0SFf8tD9bhDh9VZGdQkMT7uAoEUhNlxwzG40vnYJ6ta7bpiGBbNWGqCcyGlAG0-_8obDWktWooA02Q==", 
    "gAAAAABi1E07tO8CS9pd0SFf8tD9bhDh9VZGdQkMT7uAoEUhNlxwzG40vnYJ6ta7bpiGBbNWGqCcyGlAG0-_8obDWktWooA02Q=="],
    "5":  "isFucla332XCv34v6v4RE0aCoFjvp_HPB9vvqZ5TQgM=",  
    "6":  "MQAyADMANAA1ADYANwA4ADkA"  
}'
```
```
Invoke-WebRequest -Uri https://hostingserver/process_list.php -UseBasicParsing -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"
```
