MultiAssign
=========
ILIAS UserInterfaceHook-Plugin with the following featues:  
- Add a User so one or more courses  
- Send a Summary-Email

##Installation
###Install ActiveRecord
ILIAS 4.4 does not include ActiveRecord. Therefore please install the latest Version of active record before you install the plugin:
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Libraries/  
cd Customizing/global/plugins/Libraries  
git clone https://github.com/studer-raimann/ActiveRecord.git  
```  
###Install Plugin
Start at your ILIAS root directory  
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
git clone https://github.com/studer-raimann/MultiAssign.git  
```  
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.  

###Shot Description
![001](/doc/Screenshots/001.png?raw=true "Overview")

###Contact
studer + raimann ag  
Waldeggstrasse 72  
3097 Liebefeld  
Switzerland  

info@studer-raimann.ch
www.studer-raimann.ch