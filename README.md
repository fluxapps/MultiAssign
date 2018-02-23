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

### ILIAS Plugin SLA

Wir lieben und leben die Philosophie von Open Soure Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.

### Contact
info@studer-raimann.ch  
https://studer-raimann.ch  


[pd]: /doc/Screenshots/001.png?raw=true "personal desktop"
[user_select]: /doc/Screenshots/002.png?raw=true "Select Users"
[assign]: /doc/Screenshots/003.png?raw=true "Assign User to multiple Courses"
[conf]: /doc/Screenshots/004.png?raw=true "Plugin-Configuration"
