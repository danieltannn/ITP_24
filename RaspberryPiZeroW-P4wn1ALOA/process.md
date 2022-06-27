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
## Missing files (Error Line 215 & 216)
1. 50-bluetooth-hci-auto-poweron.rules
2. pi-bluetooth+re4son_2.2_all.deb
