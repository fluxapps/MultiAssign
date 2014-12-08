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
###Install Router
The MultiAssign-Plugin needs a Router-Service to work. Please install the Service first:
 
You start in your ILIAS root directory

```bash
cd Services  
git clone https://github.com/studer-raimann/RouterService.git Router  
```
Switch to the setup-Menu of your Installation and perform a Structure-reload in the Tab Tools. This can take a few moments. After the reload has been performed, you can install the plugin.
###Install Plugin
Start at your ILIAS root directory  
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
git clone https://github.com/studer-raimann/MultiAssign.git  
```  
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.  

##Short Description
An additional Button on the Personal Desktop of users having a given role leads to the Multi-Assign-Overview. On the overview-page the User can search for a specific user-account. After selecting the account the user is able to assign this account to multiple courses in different roles (Administrator/Tutor/Member). The selected user receives a summary-email.  
![001][pd]
![002][user_select]
![003][assign]
Have a look at the [full documentation](/doc/Documentation.pdf?raw=true)

##Contact
studer + raimann ag  
Waldeggstrasse 72  
3097 Liebefeld  
Switzerland  

info@studer-raimann.ch
www.studer-raimann.ch


[pd]: /doc/Screenshots/001.png?raw=true "personal desktop"
[user_select]: /doc/Screenshots/002.png?raw=true "Select Users"
[assign]: /doc/Screenshots/003.png?raw=true "Assign User to multiple Courses"
[conf]: /doc/Screenshots/004.png?raw=true "Plugin-Configuration"
