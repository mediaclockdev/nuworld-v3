# E-Commerce: E-Commerce Application

## Project Overview

Maroon Crane is a Laravel-based e-commerce platform. This document outlines the installation, configuration, and customizations needed to run the project.

## 1. Installation Notes

### Prerequisites

-   **Composer**: Make sure `Composer is installed` to manage dependencies.
-   **PHP**: Ensure that your system has `PHP 8.2 or higher` installed.
-   **Repository**: Repository should be downloaded from Git. Look for folders(`cache`,`views`,`sessions`) inside `storage/framework` folder. If not available, manually create them. Make sure the storage folder has write permissions.

-   **Laravel Version**: `12.18.0` (Run the command `composer install`)

### Configuration Steps

1. **Environment Configuration**
   Open `.env` and modify the following parameters as necessary:

    ```
    APP_NAME="Maroon Crane"
    APP_URL=http://localhost/maroon-crane-v2
    AUTH_PASSWORD_RESET_TOKEN_TABLE=verifications
    ENABLE_TWO_FACTOR_AUTH_ADMIN=false # Make it true if you want to receive an Email OTP
    ```

2. **Livewire Route Configuration**

    Livewire is used for Datatables only.

    - Open `livewire.php` from the `routes` folder
    - Change `/maroon-crane-v2/` to match your subdomain.
    - If not using a subdomain, you can remove this part.

3. Change the `.env` for **Google Login in Localhost** _(Change for Production)_

    ```
    GOOGLE_CLIENT_ID=586498028890-iurl1rp5ksbisvh3qnah4465lprguhpj.apps.googleusercontent.com

    GOOGLE_CLIENT_SECRET=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

    GOOGLE_REDIRECT_URI=http://localhost/maroon-crane-v2/auth/google/callback
    ```

4. Run the following commands for `final setup`
    ```
     php artisan migrate --seed
     composer dump-autoload
     php artisan optimize:clear
    ```

To run the commands using route path check web.php

## 2. Custom JS Functions for Form Inputs

-   **File Name**: `custom_input.js`
-   **File Path**: `/public/common/`
-   **Classes to Use (in Blade file)**:

    -   `only-numbers`: Allows only numbers(0-9) and decimal point, backspace, tab, delete and arrow keys.

    -   `only-integers`: Allows only integers no decimal point

    -   `only-alphabets`: Allows only alphabets (A-Z, a-z), space, backspace, tab, delete and arrow keys.

    -   `only-alphabet-numbers`: Allows only alphabets (A-Z, a-z), numbers (0-9), space, backspace, tab, delete and arrow keys.

    -   `only-alphabet-symbols`: Allows alphabets (A-Z, a-z), space, hyphen, dot, apostrophe, backspace, tab, delete and arrow keys.

    -   `only-alphabet-numbers-symbols`: Allows Alphabets, numbers, hyphen, apostrophe, period, space, ampersand

    -   `only-alphabet-unicode`: Allows Unicode letters, space, hyphen, apostrophe, quotes, period, ampersand

    -   `lowercase-slug`: Convert slug inputs to lowercase. Replaces Space with - & allows only - \_ . with alphabets and numbers.

## 3. Custom Sweet Alert2 Functions for Notifications

-   **File Name**: `custom_sweet_alert.js`, `sweetalert2.min.js`
-   **File Path**: `/public/common/`
-   **JS functions to Use (in Blade file)**:

    -   `swalNotify`: Allows to show notifications based on error or success.

        **Format**:

            swalNotify("Success!", message, "success"); // Success

            swalNotify("Oops!", message, "error"); // Errors

    -   `swalConfirm`: Allows to show the confirm button

        **Format**:

              swalConfirm("Are you sure?", "You won't be able to revert this!").then((result) => {
                // ajax call or code on yes
              }); // The default options are Yes / No.

## 4. Ids are Hashed

The project uses hashed IDs to ensure security.

-   **Package Name** : `"vinkla/hashids": "^13.0"`
-   **Salt** : Defined in the `config/hashids.php` file. You can use ur own salt.

-   **Controller codes**:

        use Vinkla\Hashids\Facades\Hashids;

        Hashids::encode($id); // To Encode an id

        Hashids::decode($id)[0]; // To Decode the id

        // Above functions can also be used in Blade files as
        {{ Hashids::encode($id) }}

## 5. Helper Functions

### 5.1 Common Helpers

-   `user('admin')`: Provides `Authenticated Admin Details`. For User no parameter is required.

-   `userNameById('admin',1)` - Fetches Admin name by `ID` (here the id is 1). For Users no parameter but id is required.

-   `siteLogo` : Fetches the `SiteLogo Path`

-   `userImageById`: Fetches Users/Admins Image by their ID.

    -   Syntax:

        -   `userImageById('admin', user('admin')->id)['image']` For Actual Image Paths

        -   `userImageById('admin', user('admin')->id)['thumbnail']` For Thumbnail Image Paths

-   `hasUserPermission` : Fetches the permission of the current Logged in User based on the route name.

    -   Syntax:
        -   `hasUserPermission('ROUTENAME')` : ROUTENAME can be like **admin.roles.create** for creatng a role etc.

-   `truncateNoWordBreak` : This function truncates a text to specified characters provided with the end symbols given. This function makes sure the last word is not trucated.

    -   Syntax: `truncateNoWordBreak($text, $limit = 250, $end = '...')`

        The default limit is set to 250 and the last trucated characters will be replaced by ...

### 5.2 Date Helpers

The formats for the date and timezone is defined in config->defaults.php

-   `convertDate($date)` : Converts date to `dd/mm/yyyy (defined in config)`

-   `convertDateTimeHours($date)` - Converts date to `dd/mm/yyyy` with `Hours`

-   `convertDateTime($date)` - Converts date to `dd/mm/yyyy` with Time in `AM/PM`

-   `formatDate($date,$format)` - For any other Format, mentioned in `$format`.
