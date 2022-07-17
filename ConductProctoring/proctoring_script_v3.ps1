# Define proctoring functions into a variable too use it in background jobs
$functions = {
    #Web Server for interval tracking
    $base = "http://24.jubilian.one/delay.txt"
    
    #---------------------------------------------------------------------------------------------------------------------
    #                                                      Basic Functions
    #---------------------------------------------------------------------------------------------------------------------
    # Function: Determine if Raspberry Pi is connected to the PC
    # Finding for USB with VID 1D6B. VID is the Vendor ID set by us when configuring composite mode on Raspberry Pi
    function Is_Connected{
        $check = $null
        $check = Get-PnpDevice -PresentOnly | Where-Object { $_.InstanceId -match '^USB\\VID_1D6B' }

        if( $null -ne $check){
            return "FOUND"
        }
    }

    #Function: Encoding plaintext string to base64 encode so that the proctoring results is not so obvious when sending to flaskserver
    function Encode($data){
        return [Convert]::ToBase64String([Text.Encoding]::Unicode.GetBytes($data))
    }

    #---------------------------------------------------------------------------------------------------------------------
    #                                                      Proctoring Functions
    #---------------------------------------------------------------------------------------------------------------------
    
    $studentId = Encode(123456789) 
    
    # Function: Display process name and the mainwindowtitle of the current active Windows.
    # Add-Type (cmdlet): Allows the definition of a Microsoft .NET Core class in Powershell session, we can then instantiate objects, by using the New-Object cmdlet and use the objects
    # Public class APIFuncs: contains three static methods that utilizes the user32.dll functions GetWindowText, GetForegroundWindow and GetWindowTextLength
    function Get_Active_Win{
        Add-Type  @"
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
"@
            while(1){
                # After calling Add-Type, we can freely use the called Windows API 
                # The two colon "::" idicates that we are calling a static .NET method
                if (Is_Connected -ne $null){
                    $w = [APIFuncs]::GetForegroundWindow() 
                    $len = [APIFuncs]::GetWindowTextLength($w) 
                    $sb = New-Object text.stringbuilder -ArgumentList ($len + 1)
                    $rtnlen = [APIFuncs]::GetWindowText($w,$sb,$sb.Capacity)
                    
                    if ([string]::IsNullOrEmpty($sb.ToString())){$sb = "No Active Window"} # Checking for active window 

                    #Sending JSON data to Flask server: By taking advantage of the POST request 
                    $data = @{AWD = Encode($($sb.tostring()))}
                    $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"
                    
                    #Appending Student Id into the received json 
                    $json_res = $response.content | ConvertFrom-Json
                    $json_res | Add-member -Name "6" -value (($studentId)) -MemberType NoteProperty

                    #Sending data to Webserver 
                    $json_data = ConvertTo-Json $json_res
                    "Current Active Windows:" | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                    $json_data | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                    Start-Sleep -s 1
                }
                else{break}
            }
    }

    # Function: Display a list of all the opened windows on the Student's PC
    function Get_Open_Win{
        while(1){
            if (Is_Connected -ne $null){
                $Windows =  Get-Process | Where-Object {$_.MainWindowTitle -ne ""} | Select-Object MainWindowTitle

                $list = New-Object Collections.Generic.List[String]
                foreach($windows in $Windows){
                    $encoded = Encode($windows.MainWindowTitle.tostring())
                    $list.Add($encoded)
                }
                
                #Sending JSON data to Flask server: By taking advantage of the POST request
                $data = @{OW = $list}
                $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"
                
                #Appending Student Id into the received json 
                $json_res = $response.content | ConvertFrom-Json
                $json_res | Add-member -Name "6" -value (($studentId)) -MemberType NoteProperty

                #Sending data to Webserver 
                $json_data = ConvertTo-Json $json_res
                "List of Opened Windows:" | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                $json_data | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                Start-Sleep -s 1
            }
            else{break}
        }
    }

    # Function: Display properties of connected monitors in Students PC.
    function Get_Display_Prop{
        while(1){
            if (Is_Connected -ne $null){
                $Monitors = (Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams | Where-Object {$_.Active -like "True"}).Active.Count

                #Sending data to Flask server: By taking advantage of the POST request 
                $data = @{AMD = Encode($($Monitors.tostring()))}
                $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"
                
                #Appending Student Id into the received json 
                $json_res = $response.content | ConvertFrom-Json
                $json_res | Add-member -Name "6" -value (($studentId)) -MemberType NoteProperty

                #Sending data to Webserver 
                $json_data = ConvertTo-Json $json_res
                "Number of Display Connected:" | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                $json_data | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                Start-Sleep -s 1
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
                
                $data = @{PL = $list}
                $response = Invoke-WebRequest -Uri http://daniel.local/ -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"
                
                #Appending Student Id into the received json 
                $json_res = $response.content | ConvertFrom-Json
                $json_res | Add-member -Name "6" -value (($studentId)) -MemberType NoteProperty

                #Sending data to Webserver 
                $json_data = ConvertTo-Json $json_res
                "List of running processes:" | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                $json_data | Out-File -FilePath C:\Users\Daniel\Desktop\test.txt -Append
                Start-Sleep -s 1
            }
            else{break}
        }
    }
}

# Main Loop: Proctoring features will be called here
# Checks: The script will be running when the Raspberry Pi Gadget is connected, else it will exit.

$job1 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Active_Win} 
$job2 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Display_Prop} 
$job3 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Proc_List} 
$job4 = Start-Job -InitializationScript $functions -ScriptBlock{Get_Open_Win} 
Wait-Job $job1 
Wait-Job $job2
Wait-Job $job3
Wait-Job $job4