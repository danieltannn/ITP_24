# Define proctoring functions into a variable
$functions = {
    # Function: Determine if Raspberry Pi is connected to the PC
    # Finding for USB with VID 1D6B. VID is the Vendor ID set by us when configuring composite mode on Raspberry Pi
    function Is_Connected{
        $check = $null
        $check = Get-PnpDevice -PresentOnly | Where-Object { $_.InstanceId -match '^USB\\VID_1D6B' }

        if( $null -ne $check){
            return "FOUND"
        }
    }

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
                    $data = "Current Active Windows Title: $($sb.tostring())"
                    $data >> C:\Users\Daniel\Desktop\test.txt 
                    Start-Sleep -s 3
                }
                else{break}
            }
    }
    # Function: Display properties of connected monitors in Students PC.
    function Get_Display_Prop{
        while(1){
            if (Is_Connected -ne $null){
                $Monitors = (Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams | Where-Object {$_.Active -like "True"}).Active.Count
                $data = "Number of connected monitors: $Monitors"
                $data >> C:\Users\Daniel\Desktop\test.txt
                Start-Sleep -s 3
            }
            else{break}
        }
    }

    # Function: Display a list of all the processes running
    function Get_Proc_List{
        while(1){
            if (Is_Connected -ne $null){
                $Process = Get-Process | Format-Wide -Property ProcessName -Column 3
                $data = $Process
                "List of Processes:" >> C:\Users\Daniel\Desktop\test.txt
                $data >> C:\Users\Daniel\Desktop\test.txt
                Start-Sleep -s 3
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
Wait-Job $job1 
Wait-Job $job2
Wait-Job $job3