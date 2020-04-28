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
### 1. getCatagory

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

### 2. nearbySellers

    params:
        lat:number,
        lng:number,
        radius:number

    request method: GET

    takes user's latitude longitude and radius under which seller to be fetched and 
    returns seller details along wth available products
    
    response structure:
    
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
    
### 3. user/login

    params:
    api_token: string

    request method : GET

    verifies the API token and get user id and fetch user details from database if user found
    then returns user details along with user's cart

    responst structure:

    Success response:
    
    {
        error: false,
        user:{ 
            id: number
            name: string
            email: string
            user_type: string
            uid: string
            address_id: number
        },
        address: {
            id: number,
            name: string,
            pin: string
            lat: number
            lng: number
            number: number
            address: string
            formatted_address: string
            city: string
            state: string
            landmark: string
            locality: string
        },
        cart: array(
            {
                cart_id: number
                item_id: number
                quantity: number
                added_at: datetime
                name: string
                image: url
                price: number
                discount: number
                seller_id: number
                stock: number
                MRP: number
            }
        )
    }
    

    Failed response:
    
    {
        error:true,
        message:string
    }
    

### 4. user/register

    params:

        api_token:string
    
    request method:POST

    register a new user and store user's details in database
    first register user with firebase auth and then sent api token with user id to store in database

    request structure:
    
    {
        json:{
            name:string,
            uid:string,
            email:string
        }
    }
    

    response structure:

    Success response:
    
    {
        registered:boolean,
        error:false
    }
    
    Failed response:
    
    {
        error:true,
        message:string
    }
    
### 5. user/addToCart

    params:

        api_token:string
    
    request method: POST

    add selected item and quantity to user's cart

    request structure:
    
    {
        json: {
            item_id:number,
            quantity:number
        }
    }
    

    response structure:

    Success response:
    
    {
        error: false
        cart_id: number
    }
    
    Failed response;

    
    {
        error:true,
        message:string
    }
    

### 6. user/placeOrder

    params:

        api_token:string

    request method:POST

    places users order to the seller 

    request structure:
    
    {
        json: {
                order:array(
                    {
                        seller_id:number,
                        items:array(
                            {
                                id:number,
                                quantity:number,
                                price:number,
                                confirmed:boolean
                            }
                        ),
                        total:{
                            total:number,
                            itemCount:number,
                            deliveryCharges:number
                        },
                        paymentMode:string,
                        status:string
                    }
                ),
                address:{
                    name:string,
                    number:number,
                    locality:string,
                    pin:number,
                    address:string,
                    city:string,
                    state:string,
                    landmark:string,
                    alternate:number,
                    lat:number,
                    lng:number,
                    updateAddress:boolean
                },
                from:string
            }
    }

    response structure:

    Success response:
    
    {
        error: false
        status: string
    }
    

    Failed response:
    
    {
        error:true,
        message:string
    }
    



## Contribution
[Adil Hussain](https://github.com/adilcse/)

## case
variable name = camelCase
database column name = snake case
