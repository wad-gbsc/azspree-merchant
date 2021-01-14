var badge_type;
var badge_text;

function CheckEmptyFields() {  
  
  fetch("api/commentslength", {
      headers: {
      Authorization: "Bearer " + localStorage.getItem("token")
      }
  })
.then(res => res.json())
  .then(data => {
    
    // comments_length = data
    // console.log(comments_length);
    if(data != null)
    {
       badge_type = "danger";
       badge_text = data;
    }
    // if(data["0"].dependents == 0)
    // {
    //   console.log('Empty fields');
    //   badge_type = "danger";
    //   badge_text = "!";
    // }
  });
}

//My function for checking if the fields are empty to show a danger badge
CheckEmptyFields() 
export default {
  items: [{
      name: 'Dashboard',
      url: '/dashboard',
      icon: 'icon-speedometer'
    },
    {
      name: 'Product',
      url: '/product',
      icon: 'icon-book-open',
      children:[
        {
          name: 'Product(s)',
          url: '/product/Products',
          icon: 'icon-list'
        },

      ]},
      
        {
        name: 'My Shop',
        url: '/Order',
        icon: 'fa fa-bars',
        children:[
          {
            name: 'Order(s)',
            url: '/shop/Orders',
            icon: 'fa fa-handshake-o'
          },
          {
            name: 'Shipment(s)',
            url: '/shop/Shipments',
            icon: 'fa fa-shopping-cart'
          },
          {
            name: 'Cancellation',
            url: '/shop/Cancellations',
            icon: 'fa fa-exclamation-circle'
          },
          {
            name: 'Category(s)',
            url: '/shop/category',
            icon: 'fa fa-code'
          },
          { 
            name: 'Comment(s)',
            url: '/shop/Comments',
            icon: 'fa fa-comments',
            badge: {
              variant: badge_type,
              text: badge_text
            },
          },
          {
            name: 'Rating(s)',
            url: '/references/categories',
            icon: 'fa fa-star-o'
          },
          {
            name: 'History Log(s)',
            url: '/shop/Logs',
            icon: 'fa fa-database',
          },
        ]},
        {
          name: 'Finance',
          url: '/references',
          icon: 'fa fa-usd',
          children:[
            {
              name: 'Income',
              url: '/references/categories',
              icon: 'fa fa-credit-card'
            },
    
        ]},
            {
              name: 'Settings',
              url: '/references',
              icon: 'fa fa-cogs',
              children:[
                {
                  name: 'Address',
                  url: '/references/categories',
                  icon: 'fa fa-address-book-o'
                },
        
              ]},
  
  ],
}