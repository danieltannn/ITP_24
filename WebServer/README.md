# Task List
- [x] Allow Administrators to dynamically alter/change the intervals' variable
- [x] Retrieve JSON data from the user's PC via the proctoring script
- [x] Decrypt the encrypted JSON data with Fernet Key [For Raw Data]
- [x] Allow Administrators to process/view the information retrieved
- [x] Store proctoring script on Web Server for the RaspberryPi to access and run without downloading
- [x] Generate Asymmetric Key Pair (RSA) for RaspberryPi to encrypt the Fernet Key with RSA's Public Key
- [x] Decrypt Fernet Key with RSA's Private Key
- [x] Implement 'Heartbeat' Protocol for Proctoring Script to indicate connection status with the RaspberryPi

---

# Web Server
Currently hosted on Hostinger for testing purposes.
Alternatively, you may host this on your own hosting environment or utilize [XAMPP](https://www.apachefriends.org/).  

## Prequisites & Resources Used
1. Web Hosting Environment
2. PHP Library (7.0 and above)
3. SQL Server (Maria DB, MySQL or any other suitable SQL Server)
4. Fernet (PHP `hash` Extension and `openssl` or `mcrypt` Extension)
   - Reference: https://github.com/kelvinmo/fernet-php
   - Used to decrypt the JSON data sent from the user's PC
5. Bootstrap (5.2)
   - Reference: https://getbootstrap.com/
   - For beautification purposes
6. DataTables
   - Reference: https://datatables.net/
   - For its functionalities, ease of use and beautification purposes  

## Features/Files
```
index.php
```
- Displays the decrypted and decoded JSON data stored inside database  
![image](https://user-images.githubusercontent.com/28032598/180282003-a66248b6-0c7e-4242-a0e5-fdf02155bd69.png)  
<br/>

```
nav_bar.php
```
- Navigation Bar used for all the pages for easy maneuvering  
<br/>

```
upload.php, upload_process.php
```
- For administrator to upload the proctoring script onto the Web Server.
- Only allows `ps1` as the file type and `proctoring_script.ps1` as the file name
- This feature should not be implemented in live production. It is purely for convenience purposes during testing only
- The proctoring script will be stored in the `uploads` folder. The `uploads` folder has to be created beforehand for this feature to work  
![image](https://user-images.githubusercontent.com/28032598/180282140-b3753334-827f-4346-b6f1-909a4a413d2f.png)  
<br/>

```
process.php, process_list.php
```
- The proctoring script on the user's PC will be sending JSON data to either `process.php` or `process_list.php` depending on whether it contains any list. `process.php` is used to process JSON data without lists while `process_list.php` is used to process JSON data with lists
- The JSON data contains a Fernet Key that is encrypted by the Raspberry Pi with our RSA public key and decrypted with our RSA private key during processing
- The rest of the data will be decrypted with the decrypted Fernet Key
- Default interval values of 300 seconds will be initialized on first instance of receiving the JSON data for a unique UUID
- Interval values will be shortened automatically when triggered, provided that administrator(s) does not override the interval values. (E.g. Detected suspicious softwares running on User's PC)
- The decoded and decrypted JSON data will then be stored on SQL Server for analysis or viewing purposes  
<br/>

```
Fernet.php
```
- Fernet: A symmetric encryption that encrypts data with a generated key
- Reference: https://github.com/kelvinmo/fernet-php
- The JSON data will be encrypted with Fernet before it is being sent over to the Web Server, alongside with the key for the Web Server to decrypt and process the data  
<br/>

```
interval.php
```
- For the proctoring script to continually poll and retrieve the interval values for the various categories and the specified UUID via GET parameters encoded with BASE64(UTF-16LE)
- The current implementation takes in the mac address of the RaspberryPi as the UUID. This should be changed to something else that can uniquely identify the student taking the examination instead in the future
- Example: `https://hostingserver.com/interval.php?uuid=MQAyADMANAA1ADYANwA4ADkA&category=QQBXAEQA`  
<br/>

```
admin_interval.php, admin_interval_delete.php, admin_interval_delete_process.php, admin_interval_edit.php, admin_interval_edit_process.php
```
- For the administrator(s) to edit or delete the interval values for the various categories
- When the parameter `admin_override` is `true` or `1`, the proctoring script will no longer be able to automatically change the interval values
![image](https://user-images.githubusercontent.com/28032598/180281714-38d4eed7-5c3e-4d08-ac92-b6485bf31872.png)  
![image](https://user-images.githubusercontent.com/28032598/180281829-c68b1959-188e-465c-810e-536973e28d8e.png)    
<br/>

```
admin_uuidlist.php, admin_uuidlist_delete.php, admin_uuidlist_delete_process.php
```
- For the administrator(s) to view the unique UUIDs and delete all data and log files pertaining to a specified UUID for housekeeping purposes  
![image](https://user-images.githubusercontent.com/28032598/180281528-2fae69eb-3050-40c1-acc4-49fa1bfb163b.png)  
<br/>

```
rsa_key_generation.php, rsa_key_generation_process.php
```
- For the administrator(s) generate an asymmetric RSA Key pair
- The Public Key of the asymmetric RSA Key Pair will be used by the RaspberryPi to encrypt the Fernet Key, which will be used to encrypt the rest of the JSON Data before forwarding it to the Web Server
- The Private Key will be kept safe on the Web Server (Invigilator's Portal) and will be used to decrypt the Fernet Key, which will then be used to decrypt the rest of the incoming JSON Data
- The Public Key and Private Key of our asymmetric RSA Key Pair are labelled `public_rsa.key` and `private_rsa.key` respectively for convenience. The label should be randomly generated in live production for security purposes 
- Logs are generated each time a new asymmetric RSA Key Pair is generated 
![image](https://user-images.githubusercontent.com/28032598/180281386-ef057798-a560-4a5d-a3a8-ac14f87c4a39.png)  
![image](https://user-images.githubusercontent.com/28032598/180284486-83a03810-e821-4a08-85eb-24621967da3f.png)  
<br/>

```
ping.php
```
- Acts as a heartbeat protocol. To receive periodic signals generated by the proctoring script to indicate normal operation
- The proctoring script will stop sending periodic signals to `ping.php` immediately when the RaspberryPi is disconnected
- The current implementation takes in the mac address of the RaspberryPi as the UUID. This should be changed to something else that can uniquely identify the student taking the examination instead in the future
- Example: `https://hostingserver/ping.php?uuid=YgA4ADoAMgA3ADoAZQBiADoANQBhADoAMQBkADoAMAAzAA==`  
<br/>

```
admin_ping_static.php, admin_ping_dynamic.php, admin_ping_process.php
```
- Displays the connection status of all RaspberryPi connected via the proctoring script (Heartbeat Protocol)
- `admin_ping_static.php` displays the connection status of all RaspberryPi at the present moment
- `admin_ping_dynamic.php` provides live updates via a Javascript function that refreshes the content in `admin-ping_process.php` every few seconds  
![image](https://user-images.githubusercontent.com/28032598/180281168-9aebe18e-9298-458f-9131-dade4c4c4e8d.png)  
<br/>

```
admin_ping_server.php, admin_ping_server_process.php
```
- `admin_ping_server.php` is to be left by the administrator during an examination or simulation to accurately capture the timestamp of the RaspberryPi connections and log them
- `admin_ping_server_process.php` contains functions that are automatically executed every few seconds by `admin_ping_server.php` to look for updates
- Live updates will be shown on the `admin_ping_server.php` page during the examination or simulation 
- The `admin_ping_server.php` page is to be closed after the examination or simulation has concluded
![image](https://user-images.githubusercontent.com/28032598/180280489-ab0bbe5a-26ef-457b-83f7-f2f9e7279d88.png) 
![image](https://user-images.githubusercontent.com/28032598/180282695-6555486e-1ae3-4bd2-b045-bdff5b1daa05.png)   
![image](https://user-images.githubusercontent.com/28032598/180284321-00fabdb9-4705-4a95-b1dd-b7cc38c571ba.png)  
<br/>

```
admin_ping_server_logs.php
```
- For the administrator(s) to easily select and view detailed RaspberryPi connection logs (Heartbeat)
![image](https://user-images.githubusercontent.com/28032598/180399720-69a510c8-15d2-4f9f-8f12-e1e89d924174.png)  
<br/>

## Test Run
Run the following commands in PowerShell after the PowerShell Execution Policy has been set to `unrestricted` or `remotesigned`
This JSON data contains a list (The fourth element) and hence, we will forward the JSON data to `process_list.php` instead
Remember to replace `https://hostingserver` with your own server's URL
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
Generated RSA Private Key (To Decrypt the Fernet Key):
```
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA99cv0nnJHwRhrQz44siz
2ALzNoqWkysPeor6yoQYGxRd4SCpe142hJ2wx5BwbmBSK+3rXdlZj18f8y/BURtC
3AEds2ls13DPv2aTH46ENt0e5wtVCb6M5BIX/l5Bp3yehxJR62lxupuRg0jApwjZ 
rXHXSS/ms3Nx6nYMluc3qCRmxk0BqdFwkaqWQ1C2E4dtJoCxplUMJ2k2WdmVx9n/
HOpbFAX54FXIYhUNlPZYMCZkzYQEFP5GAEyshCgVUiHVumc9UszHzHxo9pALYNuK
HWeIvq5B6KEswA3h4rftngI183gt/6ZaZvO89ybCHHKDjfhOiCIoUjlvuRbDrZFh
ewIDAQAB
-----END PUBLIC KEY-----
```
Generated RSA Public Key (To Encrypt the Fernet Key):
```
-----BEGIN PRIVATE KEY----- 
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQD31y/SeckfBGGt
DPjiyLPYAvM2ipaTKw96ivrKhBgbFF3hIKl7XjaEnbDHkHBuYFIr7etd2VmPXx/z
L8FRG0LcAR2zaWzXcM+/ZpMfjoQ23R7nC1UJvozkEhf+XkGnfJ6HElHraXG6m5GD
SMCnCNmtcddJL+azc3HqdgyW5zeoJGbGTQGp0XCRqpZDULYTh20mgLGmVQwnaTZZ
2ZXH2f8c6lsUBfngVchiFQ2U9lgwJmTNhAQU/kYATKyEKBVSIdW6Zz1SzMfMfGj2
kAtg24odZ4i+rkHooSzADeHit+2eAjXzeC3/plpm87z3JsIccoON+E6IIihSOW+5
FsOtkWF7AgMBAAECggEBAJt5Ci7m5xvmC8AbGyZo8SjY8OmOFtdLc/JTrO/N3bFf
HWVUr/0MJPccVQFbwqv7EJEuNzAwYNZnvgs4MfVHPLH2oUMjJ3we8LQJDNIG/TnN
jM0zdq0eNBP+pb6UMmt26ZuiCnG7O/TsEmRhBznBe4wqlfr7aaW5DgUe+NbRRUDc
GO3dODXM01SlZxxEjdOWrag0o1qqOue34w6jcjZP/As/4rdaz5602j4pGFWITIiN
XjQW2gRXtMoPoEKbNoEF4vuSLPYmhpxszvkBb8UfmdkWnsKkLQzzWv8IKHM1aab8
//WzWku52LfBFkJpNBScFLUg1klhbvxCcWEyOr7zjEECgYEA/3I3Z2T8swRfHSLQ
Z9WdxHTaQR9yKedFSvySUJ75ShU+Pc2R8GNf75FmjNAqIPYO4mEJ/ZyP1Sy45wdt
cW9TxmSmTdZCe/JjqXHRBeCU+lG5oLB8OdMGGClGdq8f7KBqBPy8m1d76xpPMPTO
oOxWVO862dD7KWgGylVcYs0P728CgYEA+GC/u7nQv4kOU5sSzIW31ghdMGtNwJz6
dLbBdh30XpSf7BzF/UYixjchb5dQA4N1K2/7aDAWzxDShiiFFP1g/Ve20dgtqQmm
GjIZriJgxVe07myG6yKBYEsKUt7r3L9iP341kfcY0oVruF9m++PXlBYDRoXjC66P
aqKFv8R4aLUCgYEA9feD08JQ9QtzjnmUVTdDCAVAl05xPlobxd24AXtiLWyRFy+X
o/H8UZEfPt2+gyiLIn4wAY08jhbXIFZtkrmQ4ErQO8jhEbpPLrySeWdL7FC/PjRS
GdfRWXh9ChEM22uHSrAV6Dpv7uzRbiF7yUZoxrXoZA4vmio5x6A0KkqsWm8CgYBz
RAL2zb7whFxftGG/BHdSHsOQitukfbVFoOKbPD+B/RtSafAAICJXNpKPxPrfBozD
wc48hcSwB7CLjhZkrUnriF9Rdy+JeO7azVFZnJ6oNpHC7B6Y1ISR+YErAEUZRsAD
k0CtIq0kVcKt56hVUWFkWerfOZcqfrIT3KSRYE442QKBgQCeNQS6AT1xNpV74D5s
DV5RJ5XHoOEqffaAz5DXssLGk+lhZGsw+OeuoBJ+9PBmulV7ojpgTwQjEEvFpus/
/XXoU4zljVGFvfK6RIgWKirV1d0Wqcqlb2TGsyTixNr4iHfE1FTGImkufE/k6/OQ
caIhb0WZRhMNvKxfxFv1fAMSHw==
-----END PRIVATE KEY-----
```

![image](https://user-images.githubusercontent.com/28032598/180282353-40cf8e68-1471-4b9d-bc52-7c03fc521129.png)  
