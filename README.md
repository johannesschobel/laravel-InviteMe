# laravel-InviteMe
Provides an invitation mechanism for Laravel applications. Note that this package **does not** handle, how the 
invitation is sent to the user (e.g., via email).

# Installation
First, add the respective line to your composer file
``` json
"require" : {
   ... ,
   "johannesschobel/laravel-inviteme": "dev-master" ,
}
```

and run `composer install` to install the new component.

Then add respective `ServiceProvider` from the package to your `config/app.php` configuration file, like this:

``` php
'providers' => [
   ... ,
   JohannesSchobel\InviteMe\InviteMeServiceProvider::class,
],
```

Then, you simply add the provided `migration` file using the following command:
```php
php artisan vendor:publish --provider="JohannesSchobel\InviteMe\InviteMeServiceProvider" --tag="migrations"
```
and then you migrate your database using
```php
php artisan db:migrate
```

If you want, you can overwrite the basic configuration of this package using the following command:

```php
php artisan vendor:publish --provider="JohannesSchobel\InviteMe\InviteMeServiceProvider" --tag="config"
```

This will copy the `inviteme` configuration file to your `config` folder. Using this file, you can 
customize the various parameters of the package. Currently, not many are available, however, I will be adding more 
and more ;)

# Usage
In order to create an `Invitation` you simply create an `new InvitationManager()`. This class manages all the provided 
functionality.

## Available Methods

### createInvitation
If you want to create an `Invitation` for a user, call this method. Respective parameters are:

```php
    @param String $email the email this invitation shall be sent (not handled by this package!)
    @param integer $days amount of days this invitation will be available
    @param object $model bind this invitation to a specific model
    @param String $custom custom data for this invitation 
    
    @return Invitation | null the created invitation
```

The main purpose, I have created this package, was that already existing Invitation packages only provides functionality 
to invite (new) users to the application / platform. **However**, they to not allow for inviting users to participate 
at a specific resource.

##### Example: 
As an editor, working on a `Dcoument`, I would like to invite other `User`s to participate working on my document.
Therefore, I would create an `Invitation` using the following code:

```php

// the document I would like to invite other users to.
$document = Document::find(1);

// get the user that shall be invited
$user = User::find(1);

$im = new InvitationManager();
$invitation = $im->createInvitation(
    $user->email,   // the email this invitation shall be sent to
    10,             // the invitation shall be available for 10 days (optional)
    $document,      // the document this user shall be invited to (optional)
    null            // additional information (optional)
);

// now do whatever you like with this invitation
// this invitation is now bound to this specific resource $document
``` 

All other methods are quite self-explaining, but I will add docs for them later on, as I continue working on this
package.