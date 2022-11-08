MultiAssign
=========
ILIAS UserInterfaceHook-Plugin with the following featues:  
- Add a User so one or more courses  
- Send a Summary-Email

# Installation
Start at your ILIAS root directory  
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
git clone https://github.com/studer-raimann/MultiAssign.git  
```  
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.  

## Short Description
An additional Button on the Personal Desktop of users having a given role leads to the Multi-Assign-Overview. On the overview-page the User can search for a specific user-account. After selecting the account the user is able to assign this account to multiple courses in different roles (Administrator/Tutor/Member). The selected user receives a summary-email.  
![001][pd]
![002][user_select]
![003][assign]
Have a look at the [full documentation](/doc/Documentation.pdf?raw=true)


[pd]: /doc/Screenshots/001.png?raw=true "personal desktop"
[user_select]: /doc/Screenshots/002.png?raw=true "Select Users"
[assign]: /doc/Screenshots/003.png?raw=true "Assign User to multiple Courses"
[conf]: /doc/Screenshots/004.png?raw=true "Plugin-Configuration"

## Rebuild & Maintenance

fluxlabs ag, support@fluxlabs.ch
This project needs to be rebuilt before it can be maintained.

Are you interested in a rebuild and would you like to participate?
Take advantage of the crowdfunding opportunity under [discussions](https://github.com/fluxapps/MultiAssign/discussions/5).

The plugins which need a rebuild and their pricing are listed here: [REBUILDS](https://github.com/fluxapps/docs/blob/8ce4309b0ac64c039d29204c2d5b06723084c64b/assets/REBUILDS.png)

Please also have a look at our other key projects and their [MAINTENANCE](https://github.com/fluxapps/docs/blob/8ce4309b0ac64c039d29204c2d5b06723084c64b/assets/MAINTENANCE.png)
