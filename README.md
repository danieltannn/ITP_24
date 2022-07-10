# 1. Achieving Composite Mode on Raspberry Pi
This section shows the different research done to achieve composite mode on Raspberry Pi Zero W.  

## 1.1. g_{hid,ether,serial,*} - not able to turn Pi into a composite mode
## 1.2. Raspberry Pi Zero W - P4wnP1
## 1.3.1. Raspberry Pi Zero W - GitLab
Records the different attempts to rebuild the P4wnP1 ALOA image file using the built script from https://gitlab.com/kalilinux/build-scripts/kali-arm/-/blob/master/raspberry-pi-zero-w-p4wnp1-aloa.sh.   
At the same time, the different errors and solution have also been noted down and explained.

## 1.3.2. Raspberry Pi Zero W - P4wnP1 A.L.O.A
Records the different attempts to rebuild the P4wnP1 ALOA image file using the built script from https://github.com/RoganDawes/P4wnP1_aloa/blob/master/build_support/rpi0w-nexmon-p4wnp1-aloa.sh  
At the same time, the different errors and solution have also been noted down and explained.

## 1.4. Libcomposite
Turn the Raspberry Pi Zero W into a composite gadget. 

Reference materials:   
- https://randomnerdtutorials.com/raspberry-pi-zero-usb-keyboard-hid/ (HID Gadget Mode)
- http://www.isticktoit.net/?p=1383 (Configuring Pi)
- https://gist.github.com/Gadgetoid/c52ee2e04f1cd1c0854c3e77360011e2, https://irq5.io/2016/12/22/raspberry-pi-zero-as-multiple-usb-gadgets/ (Configuring auto RNDIS)
- https://github.com/RoganDawes/P4wnP1/blob/master/boot/init_usb.sh (P4wnP1 Libcomposite usage example)

# 2. Conducting Procotring on Student's PC
Shows the research on different ways the Raspberry Pi Zero W can conduct proctoring on the Student's PC.
- Either dropping the proctoring scripts on the Student's PC and executing it
- Or non conventional methods such as fileless execution

# 3. Obfuscation on "Craddle" and Powershell script 
Shows the research on the different tools and methods used:
- To obfuscate the command to retrieve string in file to conduct fileless execution (More on Fileless execution in Section 2. Conducting proctoring on Stuident's PC)
- To obfuscate the procotoring script (which contains the different proctoring functions)
