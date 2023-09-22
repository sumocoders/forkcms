# OAuth Module
The oAuth module provides a way to authenticate users using OAuth instead of email and password. At this moment only Microsoft Azure is implemented.

## Azure

### Prerequisites

#### Azure Application
You will need to create an App Registration in Azure Active Directory. You can follows the steps at the official documentation: [Create a Microsoft Entra application](https://learn.microsoft.com/en-us/azure/active-directory/develop/howto-create-service-principal-portal)

You will need to name your application. Use something descriptive, for instance the address of your website. Or "Website CMS login"

A Redirect URI needs to be set. The type is web and the value is `https://<your-domain>/private/connect/azure/check`. So it will look something like `https://www.the-adress-of-your-website.be/private/connect/azure/check`.

To be able to configure the application in Fork CMS you need the following data:
* `Application (client) ID`
* `Application secret`
* `Directory (tenant) ID`

#### Composer packages
This module requires the following composer packages:
* `knpuniversity/oauth2-client-bundle`
* `thenetworg/oauth2-azure`

### Setup
After installing the module, it still needs to be setup. got to `https://<your-domain>/private/en/o_auth/module_settings` and add your `client_id`, `secret` and `tenant`.
To enable the login button on the login page also enable the `enabled` setting.

#### Setup Groups
The oAuth module needs to know which groups to add users to. To do this go to `https://<your-domain>/en/backend/user_group_index` and edit the groups you want to use.
When the oAuth module is enabled a new field wil be present on the edit page. This field is called `oAuth role` and should contain the `value` of the group in Azure.
On login the user will be added to the groups that match the `value` of the group in Azure.
If no group is found the login will fail.

#### Setup security.yaml
Update the following line in `config/packages/security.yaml`. This will allow both off the authenticators to be used.
```yaml
custom_authenticator: ForkCMS\Modules\Backend\Domain\Authentication\BackendAuthenticator
```

to 
```yaml
custom_authenticators:
  - ForkCMS\Modules\OAuth\Domain\Authentication\AzureAuthenticator
  - ForkCMS\Modules\Backend\Domain\Authentication\BackendAuthenticator
entry_point: ForkCMS\Modules\Backend\Domain\Authentication\BackendAuthenticator
```
#### Setup services.yaml
Add the following services to `config/services.yaml`. This wil make it possible to install the module at any time without breaking te needed services in the security.yaml file.
```yaml
ForkCMS\Modules\OAuth\Domain\OAuth\AzureProviderFactory:
  tags:
    - { name: oauth.provider_factory }

ForkCMS\Modules\OAuth\Domain\Authentication\AzureAuthenticator:
  tags:
    - { name: security.authenticator }

TheNetworg\OAuth2\Client\Provider\Azure:
  factory: [ '@ForkCMS\Modules\OAuth\Domain\OAuth\AzureProviderFactory', 'create' ]
```

#### Changes to other modules
To make sure the oAuth module works correctly some changes have been made to other modules.
* src/Modules/Backend/templates/Backend/login.html.twig (added login button)
* src/Modules/Backend/templates/base/formTheme.html.twig (add additional form fields, disable checkboxes in user groups)
* src/Modules/Backend/Domain/UserGroup/UserGroup.php (added oAuth role field)
* src/Modules/Backend/Domain/UserGroup/UserGroupType.php (added oAuth role field)
* src/Modules/Backend/Domain/UserGroup/UserGroupDataTransferObject.php (added oAuth role field)

