# Achieving Composite Mode on Raspberry Pi
This section shows the different research done to achieve composite mode on Raspberry Pi Zero W.  

## 1. g_{hid,ether,serial,*} - not able to turn Pi into a composite mode
## 2. Raspberry Pi Zero W - P4wnP1
## 3.1 Raspberry Pi Zero W - GitLab
Records the different attempts to rebuild the P4wnP1 ALOA image file using the built script from https://gitlab.com/kalilinux/build-scripts/kali-arm/-/blob/master/raspberry-pi-zero-w-p4wnp1-aloa.sh.   
At the same time, the different errors and solution have also been noted down and explained.

## 3.2 Raspberry Pi Zero W - P4wnP1 A.L.O.A
Records the different attempts to rebuild the P4wnP1 ALOA image file using the built script from https://github.com/RoganDawes/P4wnP1_aloa/blob/master/build_support/rpi0w-nexmon-p4wnp1-aloa.sh  
At the same time, the different errors and solution have also been noted down and explained.

## 4. Libcomposite
Turn the Raspberry Pi Zero W into a composite gadget. 

Reference materials:   
- https://randomnerdtutorials.com/raspberry-pi-zero-usb-keyboard-hid/ (HID Gadget Mode)
- http://www.isticktoit.net/?p=1383 (Configuring Pi)
- https://gist.github.com/Gadgetoid/c52ee2e04f1cd1c0854c3e77360011e2, https://irq5.io/2016/12/22/raspberry-pi-zero-as-multiple-usb-gadgets/ (Configuring auto RNDIS)
- https://github.com/RoganDawes/P4wnP1/blob/master/boot/init_usb.sh (P4wnP1 Libcomposite usage example)

# Conducting Procotring on Student's PC
Shows the research on different ways the Raspberry Pi Zero W can conduct proctoring on the Student's PC  

**Prerequisite:**
- The Raspberry Pi has already been configured to achieve composite mode (See Libcomposite for more info)
- The Raspberry Pi has internet connection

## 1. Fileless Execution
Conducting the proctoring without needing to cpy external binary executable to devices. Instead, use existing software, particularly Powershell, to download additional scripts and run them purely in memory. 

Reference materials:
- https://www.mcafee.com/enterprise/en-us/assets/solution-briefs/sb-fileless-malware-execution.pdf
- https://www.varonis.com/blog/fileless-malware
