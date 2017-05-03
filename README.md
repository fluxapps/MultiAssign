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

### Hinweis Plugin-Patenschaft
Grundsätzlich veröffentlichen wir unsere Plugins (Extensions, Add-Ons), weil wir sie für alle Community-Mitglieder zugänglich machen möchten. Auch diese Extension wird der ILIAS Community durch die studer + raimann ag als open source zur Verfügung gestellt. Diese Plugin hat noch keinen Plugin-Paten. Das bedeutet, dass die studer + raimann ag etwaige Fehlerbehebungen, Supportanfragen oder die Release-Pflege lediglich für Kunden mit entsprechendem Hosting-/Wartungsvertrag leistet. Falls Sie nicht zu unseren Hosting-Kunden gehören, bitten wir Sie um Verständnis, dass wir leider weder kostenlosen Support noch Release-Pflege für Sie garantieren können.

Sind Sie interessiert an einer Plugin-Patenschaft (https://studer-raimann.ch/produkte/ilias-plugins/plugin-patenschaften/ ) Rufen Sie uns an oder senden Sie uns eine E-Mail.

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
