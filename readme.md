# Coffee store
This e-commerce project has been built entirely using plain PHP, MySQL database, and bootstrap. 

## A short introduction
When you enter the main page you need to create a new account or log in to an existing one. 
![login](/ss/login.png)

There are two types of accounts:
- Users can only order from the store. 
- Admins can access dashboard (user will be redirected to the main page) from which they can add, modify or delete:
    - Products
        ![admin-products](/ss/admin-products.png)
        ![admin-products-add](/ss/admin-products-add.png)
    - Categories 
        ![admin-categories](/ss/admin-categories.png)
    - Companies 
        ![admin-companies](/ss/admin-companies.png)

## Orders
The client can choose coffee from the product list on the main page
![main-page](/ss/main-page.png)

and add it to the cart
![cart](/ss/cart.png)

When the user didn't provide a shipping address the next step will automatically redirect him to a page with address details.
![edit-user](/ss/edit-user.png)

In the order summary, the client can choose their address and confirm their purchase.
![order](/ss/order.png)

## Database 
PHP script connects to a locally hosted MySQL database by a specially created user called shop.

### Database structure
![relations](/ss/relations.png)