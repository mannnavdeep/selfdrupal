# BE-Developer-Test
BE Developer Test [2-5 Years]

## Assignment for developer

### Install this site on your local machine
This Drupal 10 site is based on DDEV/Composer. So
Clone the repository
Run ddev [DDEV with Docker installation needed], in case you want to use DB attached in the repository, can ignore DDEV but you have to run composer

### Setup a Drupal system with custom content types based on Organization departments

##### Sales
Sale ID, product ID, price, description, tax

##### Production
production id, product ID, price, manufactured datetime, description, category (a taxonomy)

#### Create views for all the above content types where records can be listed with option to sort by creation date, name, price etc. Add a range filter to drill down records created between certain dates and a category filter for the Production list view page

#### Create roles and attach to content types such that, no one can edit/delete other departments' data, the view can be public.

#### Add-on [Nice to do]: create a workflow for sending emails to the sales head when a new quote is generated from sales team.

### When you are done, send the code/configuration to the shared email id, a wetransfer/dropbox/googledrive link.

Admin Credentials
admin / Admin@1409

Sales user credentials
sales_person / sales@123

Production user credentials
production_manager / production@123

Sales view - /admin/structure/views/view/sales
Production view - /admin/structure/views/view/production

To check email functionality kindly create a new content of sales content type you will receive the email. Kindly set your emailid in 'Send To' field.

admin/config/workflow/rules/reactions/edit/send_email_on_new_quote/edit/f23ef4f9-901b-4131-8d79-17ccc4e7c19f

and configure your smtp configurations.

Kindly import drupal_ten.sql file.
