# Tool One: Invoke-Obfuscation
## Used for obfuscating the command that the HID script will run to retrieve string from file and execute string. 

### 1. Token 
- String (Obfuscation for string in command)
1.1 Concatenate: coffee -> ('co'+'ffe'+'e')  
1.2 Reorder: coffee -> ('{1}{0}'-f'ffee','co')  

- Command (Obfuscation for built-in powershell commands)
2.1 Ticks- Adding tick marks to commands: New-Object ->  Ne`w-O`Bject  
2.2 Splatting + Concatenate(from string) - Splitting commands into string objects and concatenating: New-Object -> &('Ne'+'w-Ob'+'ject')  
2.3 Splatting + Reorder(from string) - Splitting commands into string objects and reordering: New-Object -> &('{1}{0}'-f'bject','New-O')
