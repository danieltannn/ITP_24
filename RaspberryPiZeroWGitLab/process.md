# Build script source origin
https://gitlab.com/kalilinux/build-scripts/kali-arm/-/blob/master/raspberry-pi-zero-w-p4wnp1-aloa.sh

# Machine used to build script on: Kali Linux
OS: Kali GNU/Linux Rolling  
Kernel: Linux 5.16.0-kali7-amd64  
Architecture: x86-64  

# Build Process
Based on Building instructions under https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master  
```
cd ~/
git clone https://gitlab.com/kalilinux/build-scripts/kali-arm
cd ~/kali-arm/
sudo ./common.d/build_deps.sh
sudo ./raspberry-pi-zero-w-p4wnp1-aloa.sh v1.0 (script requires one argument at least)
```

# Troubleshooting Process
## Kernel Build Issue
Kernel built successfully, however multiple issues were encountered in the process of building the kernel 
