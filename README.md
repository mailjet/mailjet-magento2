# mailjet-magento2
The official Mailjet plugin for Magento 2

## Installation instructions 

1. Back up your database (optional) - if you want to be sure that no undesired changes  would occur 
2. Set up your cron jobs -  
https://devdocs.magento.com/guides/v2.4/config-guide/cli/config-cli-subcommands-cron.html 
3. Login into SSH and navigate to your store root directory 
4. Enable maintenance mode: `php bin/magento maintenance:enable` 
5. You can upload your module’s files via FTP or with composer: 
   1. FTP 
      1. Download your module archive from your Magento store purchases 
      2. Connect to your hosting server with Filezilla or another FTP client 
      3. Navigate to `your_store_root_directory/app/code`
      4. Inside create a folder “Mailjet” and enter it 
      5. Inside of the “Mailjet” folder create a new folder “Mailjet” and enter it
      6. Extract the files on your local PC 
      7. Upload the module’s files to the opened directory on the FTP client
      8. Go to your CLI and download the Mailjet library `composer require mailjet/mailjet-apiv3-php`
   2. Composer
      1. Update the composer.json file in your Magento project with the name  of the extension
      2. Add the extension’s name and version to your composer.json file and  download the needed packages: 
      ```
      composer require mailjet/mailjet-magento2 --no-update
      composer update mailjet/mailjet-magento2
      ```
      3. Enter your authentication keys. Your public key is your username;  your private key is your password
         1. To get them, go to Magento **Marketplace > Your name > My  Profile**
         2. Click **Access Keys** in the Marketplace tab
         3. If you don’t have Access Keys click **Create a New Access  Key**. Enter a specific name for the keys (e.g., the name of the  developer receiving the keys) and click OK
         4. New public and private keys are now associated with your  account that you can click to copy. Save this information or keep the page open when working with your Magento project. Use the Public key as your username and the Private key as your password
      4. Wait for Composer to finish updating your project dependencies and  make sure there aren’t any errors 
6. Verify the extension
   1. To verify that the extension installed properly, run the following command:
   `php bin/magento module:status Mailjet_Mailjet`
   2. By default, the extension is probably disabled:  
   `Module is disabled`
7. Enable the extension
   1. Enable the extension and clear static view files: `php bin/magento module:enable Mailjet_Mailjet --clear-static-content`
   2. Register the extension: `php bin/magento setup:upgrade`
   3. Recompile your Magento project: in Production mode, you may receive a  message to “Please rerun Magento compile command”. Magento does not  prompt you to run the compile command in Developer mode: `php bin/magento setup:di:compile`
   4. Verify that the extension is enabled: `php bin/magento module:status Mailjet_Mailjet` You should see output verifying that the extension is no longer disabled: `Module is enabled`
   5. Clean the cache: `php bin/magento cache:clean`
      1. Disable maintenance mode: `php bin/magento maintenance:disable`
      2. Configure the extension in Admin as needed: go to **Admin panel > Stores >  Configuration > Mailjet **

Note: You need to have a mailjet account to set your API settings.
