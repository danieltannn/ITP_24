# Build script source origin
https://github.com/lgeekjopt/P4wnP1_aloa/blob/master/build_support/rpi0w-nexmon-p4wnp1-aloa.sh

# Machine used to build script on: Kali Linux
OS: Kali GNU/Linux Rolling  
Kernel: Linux 5.16.0-kali7-amd64  
Architecture: x86-64  

# Build Process
```
git clone https://github.com/lgeekjopt/P4wnP1_aloa.git clear
cd P4wnP1_aloa/build_support
sudo ./rpi0w-nexmon-p4wnp1-aloa.sh 1.0 (1.0 is the version number)
```

# Troubleshooting Process
## Error 1: Missing files (Error Line 215 & 216 & 220)
**Files missing**: 50-bluetooth-hci-auto-poweron.rules & pi-bluetooth+re4son_2.2_all.deb & config.txt 
Logs for error can be found on: error_log_1
**Solution**: Downloaded required file from and saving it to /misc/pi-bluetooth and /misc
1. https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master/bsp/bluetooth/rpi (50-bluetooth-hci-auto-poweron.rules & pi-bluetooth+re4son_2.2_all.deb)
2. https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master/bsp/firmware/rpi (config.txt)

