# Tool One: Invoke-Obfuscation

### 1. Token 
- String (Obfuscation for string in command)
1.1 Concatenate: coffee -> ('co'+'ffe'+'e')  
1.2 Reorder: coffee -> ('{1}{0}'-f'ffee','co')  

- Command (Obfuscation for built-in powershell commands)
2.1 Ticks - Adding tick marks to commands: New-Object ->  Ne`w-O`Bject  
2.2 Splatting + Concatenate(from string) - Splitting commands into string objects and concatenating: New-Object -> &('Ne'+'w-Ob'+'ject')  
2.3 Splatting + Reorder(from string) - Splitting commands into string objects and reordering: New-Object -> &('{1}{0}'-f'bject','New-O')

- Argument, Member (Obfuscation for arguments in built-in powershell commands)
3.1 Random Case - Randomly captitalize: Net.WebClient -> nEt.weBclIenT
3.2 Ticks - Randomly adding ticks: Net.WebClient -> nE`T.we`Bc`lIe`NT
3.3 Concatenate: Net.WebClient -> ('Ne'+'t.We'+'bClient')
3.4 Reorder: Net.WebClient -> ('{1}{0}'-f'bClient','Net.We')

- Variable (Variables in powershell script)
4.1 Random Case + {} + Ticks: $chemex -> ${c`hEm`eX}

- Type []

