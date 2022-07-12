# Tool: Invoke-Obfuscation
## Can be used for command block or an entire script 

### 1. Token - Obfuscate the command itself by hiding parts by parts listed below
- String (Obfuscation for string in command)
1.1 Concatenate: coffee -> ('co'+'ffe'+'e')  
1.2 Reorder: coffee -> ('{1}{0}'-f'ffee','co')  

- Command (Obfuscation for built-in powershell commands)
1.1 Ticks - Adding tick marks to commands: New-Object ->  Ne`w-O`Bject  
1.2 Splatting + Concatenate(from string) - Splitting commands into string objects and concatenating: New-Object -> &('Ne'+'w-Ob'+'ject')  
1.3 Splatting + Reorder(from string) - Splitting commands into string objects and reordering: New-Object -> &('{1}{0}'-f'bject','New-O')  

- Argument, Member (Obfuscation for arguments in built-in powershell commands)  
1.1 Random Case - Randomly captitalize: Net.WebClient -> nEt.weBclIenT  
1.2 Ticks - Randomly adding ticks: Net.WebClient -> nE\`T.we\`Bc\`lIe\`NT  
1.3 Concatenate: Net.WebClient -> ('Ne'+'t.We'+'bClient')  
1.4 Reorder: Net.WebClient -> ('{1}{0}'-f'bClient','Net.We')  

- Variable (Variables in powershell script)  
1.1 Random Case + {} + Ticks: \$chemex -> ${c\`hEm\`eX}  

- Type (Obfuscation for type in powershell)
1.1 Cast + Concatenate: Similar to String \[Type]('Con'+'sole')  
1.2 Cast + Reorder: Similar to String \[Type]('{1}{0}'-f'sole','Con')

- Comment (Comments in powershell script)  
1.1 Remove comments

- Whitespace  
1.1 Adding random whitespaces

### 2. String - Obfuscate the entire command as a String, not the same as Token(String)
- Concatenate: Concatenate entire string  
- Reorder: Reorder entire command after concatenating  
- Reverse: Reverse entire command after concatenating  (Convernt command to string and then flip it. Like abc -> cba)
  - ```Write-Host "Hello"``` -> ``` inVoKe-eXpRESsIOn ( ([RegEx]::MatCHes(")'x'+]31[DILLEHS$+]1[dILLeHs$ ( . | )43]RAhC[,)86]RAhC[+68]RAhC[+301]RAhC[(ecAlPeR-  )'DVgo'+'l'+'leHDV'+'g'+' '+'tsoH-'+'etirW'((", '.' , 'RigH'+'ttO'+'l'+'eft' ) -JOin'' )  )```

### 3. Encoding 
- ASCII
- Hex
- Octal
- Binary
- SecureString (AES)
- BXOR: bitwise BXOR
- Special Characters: https://perl-users.jp/articles/advent-calendar/2010/sym/11
- Whitespace: Encodes the powershell script with whitespaces and tabs. 

### 4. Compress - Convert entire command or powershell script into a one-liner and compress it 
This is done through firstly compressing the powershell script or command then base64 encodes it.

# Testings: Timing of decode and translate to execution
## It is important to obfuscate powershell script and cradle but in the event of too many layers of obfuscation the execution speed will significantly increase
### Base without any obfuscation  
| Testings | Timing | 
| --- | --- |
| Test 1 | 1.356 seconds  | 
| Test 2 | 1.395 seconds  | 
| Test 3 | 1.339 seconds  | 
| Average | 

### String obfuscation (concatenate only)
| Testings | Timing | 
| --- | --- |
| Test 1 | 2.549 seconds  | 
| Test 2 | 2.972 seconds  | 
| Test 3 | 2.733 seconds  |

### String obfuscation (concatenate and reverse)
| Testings | Timing | 
| --- | --- |
| Test 1 | 2.594 seconds  | 
| Test 2 | 2.869 seconds  | 
| Test 3 | 2.850 seconds  |

### String obfuscation (concatenate and reorder)
| Testings | Timing | 
| --- | --- |
| Test 1 | 2.517 seconds  | 
| Test 2 | 2.673 seconds  | 
| Test 3 | 3.101 seconds  |

### Encoding (ASCII)
| Testings | Timing | 
| --- | --- |
| Test 1 | 2.456 seconds  | 
| Test 2 | 2.851 seconds  | 
| Test 3 | 2.595 seconds  |

### Encoding (Hex)
| Testings | Timing | 
| --- | --- |
| Test 1 | 1.614 seconds  | 
| Test 2 | 1.581 seconds  | 
| Test 3 | 1.591 seconds  |

### Encoding (Octal)
| Testings | Timing | 
| --- | --- |
| Test 1 | 1.751 seconds  | 
| Test 2 | 1.640 seconds  | 
| Test 3 | 1.559 seconds  |

### Encoding (Binary)
| Testings | Timing | 
| --- | --- |
| Test 1 | 5.104 seconds  | 
| Test 2 | 5.772 seconds  | 
| Test 3 | 5.659 seconds  |

### Encoding (SecureString AES)
| Testings | Timing | 
| --- | --- |
| Test 1 | 3.199 seconds  | 
| Test 2 | 2.920 seconds  | 
| Test 3 | 3.195 seconds  |

### Encoding (Binary XOR)
| Testings | Timing | 
| --- | --- |
| Test 1 | 7.396 seconds  | 
| Test 2 | 7.124 seconds  | 
| Test 3 | 6.645 seconds  |

### Encoding (Special Characters)
| Testings | Timing | 
| --- | --- |
| Test 1 | 5.908 seconds  | 
| Test 2 | 6.230 seconds  | 
| Test 3 | 5.752 seconds  |

### Encoding (White Space)
| Testings | Timing | 
| --- | --- |
| Test 1 | 2.832 seconds  | 
| Test 2 | 3.690 seconds  | 
| Test 3 | 3.707 seconds  |

### String obfuscation (concatenate and reverse entire script) + Encoding (White Spaces)
| Testings | Timing | 
| --- | --- |
| Test 1 | 7.979 seconds  | 
| Test 2 | 8.633 seconds  | 
| Test 3 | 8.626 seconds  | 

### Multiple Layers of String obfuscation (Reorder then reverse then reorder again) 
| Testings | Timing | 
| --- | --- |
| Test 1 | 3.876 seconds  | 
| Test 2 | 3.163 seconds  | 
| Test 3 | 3.228 seconds  |

# Findings and explanation
## Proctoring Script
Since the purpose of obfuscating this script is to make it unreadable as a whole and hard to decode and translate. I did not bother with obfuscating tokens which will look into the script and obfuscate every powershell command based on their types as stated. (command, argument, variable, etc).  

However I will be obfuscating the comments.  

Instead I will be using the String Obfuscation along side the Encoding Obfuscation to encode and transform the protoring script into an unreadable format so in the event when a student got a hold onto the script, they would not be able to read it and easily decode and translate the script.  
