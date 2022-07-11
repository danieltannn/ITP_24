# Tool One: Invoke-Obfuscation
## Can be used for command block or an entire script 

### 1. Token 
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
1.1 Random Case + {} + Ticks: $chemex -> ${c\`hEm\`eX}  

- Type (Obfuscation for type in powershell)
1.1 Cast + Concatenate: Similar to String \[Type]('Con'+'sole')  
1.2 Cast + Reorder: Similar to String \[Type]('{1}{0}'-f'sole','Con')

- Comment (Comments in powershell script)  
1.1 Remove comments

- Whitespace  
1.1 Adding random whitespaces

### 2. String
- Concatenate: Concatenate entire string  
- Reorder: Reorder entire command after concatenating  
- Reverse: Reverse entire command after concatenating  

### 3. Encoding 
- ASCII
- Hex
- Octal
- Binary
- SecureString (AES)
- BXOR: bitwise BXOR
- Special Characters: https://perl-users.jp/articles/advent-calendar/2010/sym/11
- Whitespace

### 4. Compress - Convert entire command or powershell script into a one-liner and compress it 
This is done through firstly compressing the powershell script or command then base64 encodes it.
