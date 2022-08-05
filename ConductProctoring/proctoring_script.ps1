# Define proctoring functions into a variable too use it in background jobs
$functions = {
    #---------------------------------------------------------------------------------------------------------------------
    #                                                   Variables Definition
    #---------------------------------------------------------------------------------------------------------------------
    #Web Server for interval tracking
    $interval_base = 'https://24.jubilian.one/interval.php?'
    #Web server for processing string data
    $sending_base = 'https://24.jubilian.one/process.php'
    #Web server for processing list data
    $sending_list_base = 'https://24.jubilian.one/process_list.php'
    #Webserver for getting public key
    $key = 'https://24.jubilian.one/RSA/public_rsa.key'
    #Heartbeat 
    $heartbeat = 'https://24.jubilian.one/ping.php?'

    #---------------------------------------------------------------------------------------------------------------------
    #                                                      Basic Functions
    #---------------------------------------------------------------------------------------------------------------------
    # Function: Determine if Raspberry Pi is connected to the PC
    # Finding for USB with VID 1D6B. VID is the Vendor ID set by us when configuring composite mode on Raspberry Pi
    function Is_Connected{
        $check = $null
        $check = Get-PnpDevice -PresentOnly | Where-Object { $_.InstanceId -match '^USB\\VID_1D6B' }

        if( $null -ne $check){
            return 'FOUND'
        }
    }

    #Function: Encoding plaintext string to base64 encode so that the proctoring results is not so obvious when sending to flaskserver
    function Encode($data){
        return [Convert]::ToBase64String([Text.Encoding]::Unicode.GetBytes($data))
    }

    #---------------------------------------------------------------------------------------------------------------------
    #                                                    Initial Functions
    #---------------------------------------------------------------------------------------------------------------------
    #Retrieving publick key from web server
    $pub_key = Invoke-WebRequest -Uri $key -UseBasicParsing

    # Sending public key to Pi's Flask server in JSON format            
    $key_data = @{PuK = Encode($($pub_key.tostring()))}
    # Result is the UUID 
    $completed = $false

    if (Is_Connected -ne $null){
        try{
            $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($key_data|ConvertTo-Json) -ContentType 'application/json'
            $uuid = ($response.content | ConvertFrom-Json).uuid
            $completed = $true
        }
        catch{}
    }
    else{break}
    
    #---------------------------------------------------------------------------------------------------------------------
    #                                                     Heartbeat Functions
    #---------------------------------------------------------------------------------------------------------------------
    #Sending heartbeat to webserver to signify that 'I am still connected'
    #It will be using the unique MAC address to differentiate the different devices
    function Send_HeartBeat{
        while(1){
            if (Is_Connected -ne $null){
                $url = $heartbeat + 'uuid=' + $uuid
                Invoke-WebRequest -Uri $url -UseBasicParsing
                Start-Sleep -s 5
            }
            else{break}
        }
    }

    #---------------------------------------------------------------------------------------------------------------------
    #                                                      Proctoring Functions
    #---------------------------------------------------------------------------------------------------------------------
    # Function: Display process name and the mainwindowtitle of the current active Windows.
    # Add-Type (cmdlet): Allows the definition of a Microsoft .NET Core class in Powershell session, we can then instantiate objects, by using the New-Object cmdlet and use the objects
    # Public class APIFuncs: contains three static methods that utilizes the user32.dll functions GetWindowText, GetForegroundWindow and GetWindowTextLength
    function Get_Active_Win{
        Add-Type  @'
        using System;
        using System.Runtime.InteropServices;
        using System.Text;
        
        public class APIFuncs
        {
            [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
                public static extern int GetWindowText(IntPtr hwnd,StringBuilder lpString, int cch);
        
            [DllImport("user32.dll", SetLastError=true, CharSet=CharSet.Auto)]
                public static extern IntPtr GetForegroundWindow();
        
            [DllImport("user32.dll", SetLastError=true, CharSet=CharSet.Auto)]
                public static extern Int32 GetWindowTextLength(IntPtr hWnd);
            }
'@
            while(1){
                # After calling Add-Type, we can freely use the called Windows API 
                # The two colon '::' idicates that we are calling a static .NET method
                if (Is_Connected -ne $null){
                    $w = [APIFuncs]::GetForegroundWindow() 
                    $len = [APIFuncs]::GetWindowTextLength($w) 
                    $sb = New-Object text.stringbuilder -ArgumentList ($len + 1)
                    $rtnlen = [APIFuncs]::GetWindowText($w,$sb,$sb.Capacity)
                    
                    if ([string]::IsNullOrEmpty($sb.ToString())){$sb = 'No Active Window'} # Checking for active window 

                    $completed = $false
                    #Sending JSON data to Flask server: By taking advantage of the POST request 
                    $data = @{AWD = Encode($($sb.tostring()))}
                    
                    while (-not $completed){
                        try{
                            $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType 'application/json'
                            #Sending data to Webserver 
                            Invoke-WebRequest -Uri $sending_base -UseBasicParsing -Method POST -Body ($response.content|ConvertTo-Json) -ContentType 'application/json'
                            $completed = $true
                        }catch{}
                    }
                    #Retrieving interval 
                    $tag = Encode('AWD')
                    $url = ($interval_base + 'uuid=' + $uuid + '&category=' + $tag)
                    $delay = Invoke-WebRequest -Uri $url -UseBasicParsing
                    Start-Sleep -s $delay.content
                    
                }
                else{break}
            }
    }

    # Function: Display a list of all the opened windows on the Student's PC
    function Get_Open_Win{
        while(1){
            if (Is_Connected -ne $null){
                $Windows =  Get-Process | Where-Object {$_.MainWindowTitle -ne ''} | Select-Object MainWindowTitle

                $list = New-Object Collections.Generic.List[String]
                foreach($windows in $Windows){
                    $encoded = Encode($windows.MainWindowTitle.tostring())
                    $list.Add($encoded)
                }
                
                $completed = $false
                #Sending JSON data to Flask server: By taking advantage of the POST request
                $data = @{OW = $list}

                while (-not $completed){
                    try{
                        $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType 'application/json'
                        
                        #Sending data to Webserver 
                        Invoke-WebRequest -Uri $sending_list_base -UseBasicParsing -Method POST -Body ($response.content|ConvertTo-Json) -ContentType 'application/json'
                        $completed = $true
                    }catch{}
                }
                #Retrieving interval
                $tag = Encode('OW')
                $url = ($interval_base + 'uuid=' + $uuid + '&category=' + $tag)
                $delay = Invoke-WebRequest -Uri $url -UseBasicParsing
                Start-Sleep -s $delay.content
            }
            else{break}
        }
    }

    # Function: Display properties of connected monitors in Students PC.
    function Get_Display_Prop{
        while(1){
            if (Is_Connected -ne $null){
                $Monitors = (Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams | Where-Object {$_.Active -like 'True'}).Active.Count

                $completed = $false
                #Sending data to Flask server: By taking advantage of the POST request 
                $data = @{AMD = Encode($($Monitors.tostring()))}
                
                while (-not $completed){
                    try{
                        $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType 'application/json'

                        #Sending data to Webserver 
                        Invoke-WebRequest -Uri $sending_base -UseBasicParsing -Method POST -Body ($response.content|ConvertTo-Json) -ContentType 'application/json'
                        $completed = $true
                    }catch{}
                }    
                #Retrieving interval
                $tag = Encode('AMD')
                $url = ($interval_base + 'uuid=' + $uuid + '&category=' + $tag)
                $delay = Invoke-WebRequest -Uri $url -UseBasicParsing
                Start-Sleep -s $delay.content
            }
            else{break}
        }
    }

    # Function: Display a list of all the processes running
    function Get_Proc_List{
        while(1){
            if (Is_Connected -ne $null){
                $Process = Get-Process | Group-Object ProcessName | Select-Object Name

                $list = New-Object Collections.Generic.List[String]
                foreach($process in $Process){
                    $encoded = Encode($process.name.tostring())
                    $list.Add($encoded)
                }
                
                $completed = $false
                #Sending data to Flask server: By taking advantage of the POST request 
                $data = @{PL = $list}

                while (-not $completed){
                    try{
                        $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType 'application/json'

                        #Sending data to Webserver 
                        Invoke-WebRequest -Uri $sending_list_base -UseBasicParsing -Method POST -Body ($response.content|ConvertTo-Json) -ContentType 'application/json'
                        $completed = $true
                    }catch{} 
                }
                #Retrieving interval
                $tag = Encode('PL')
                $url = ($interval_base + 'uuid=' + $uuid + '&category=' + $tag)
                $delay = Invoke-WebRequest -Uri $url -UseBasicParsing
                Start-Sleep -s $delay.content
            }
            else{break}
        }
    }
}

#---------------------------------------------------------------------------------------------------------------------
#                                                    Background Jobs Activation
#---------------------------------------------------------------------------------------------------------------------
$heartbeat = Start-Job -InitializationScript $functions -ScriptBlock{Send_HeartBeat}
Start-Sleep 2
$job1 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Active_Win}
Start-Sleep 2
$job2 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Display_Prop} 
Start-Sleep 2
$job3 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Proc_List} 
Start-Sleep 2
$job4 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Open_Win} 
Start-Sleep 2
wait-Job $heartbeat 
Wait-Job $job1 
Wait-Job $job2 
Wait-Job $job3 
Wait-Job $job4 