# Lumen PHP Framework
PHP lumen API for grocery app.
It has 2 section :


1.USER
2.SELLER


user can register, siginin, get nearby sellers,
add item in Cart, update Cart, remove item from cart,
Place order, add address, update address,
Edit profile and get orders

Similarly
Seller can register ,signin,get seller products,
get orders placed to the seller, add items, delete items
edit or update items.

When user places an order it autometically reflects to the corresponding seller page
when seller accepts the order then stock of item automatically updated accordingly.
order only accepted if enough stock available .

# API endpoints
1. getCatagory

    params: null

    retquest method: GET

    Takes no arguments and returns with an array of catagory available for the products

    response structure:
    {

        error: boolean,

        caragory: array(

                        id:number,

                        name:string

                        )

    }

2. nearbySellers

    params:

        lat:number,

        lng:number,

        radius:number
    
    request method: GET

    takes user's latitude longitude and radius under which seller to be fetched and 
    returns seller details along wth available products
    
    response structure:
    ```
    {

        error: boolean,

        seller:array(distance: number,

                    shop_name: string,

                    email: string,

                    id: number,

                    seller_name: string,

                    pin: number,

                    lat: number,

                    lng: number,

                    number: number,

                    city: string,

                    state: string,

                    landmark: string,

                    locality: string

                    ),

        products:array(id: number,
                        seller_id: number,
                        name: string,
                        description: string,
                        image: url,
                        price: number,
                        discount: number,
                        stock: number,
                        catagory_id: number,
                        created_at: date,
                        updated_at: date,
                        MRP: number,
                        )
    }
    ```
## Contribution
[Adil Hussain](https://github.com/adilcse/)

## case
variable name = camelCase
database column name = snake case
