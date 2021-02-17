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
          icon: 'icon-list',
          type: [0 , 2]
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
            icon: 'fa fa-handshake-o',
            type: [0 , 2]
          },
          
          {
            name: 'Shipment(s)',
            url: '/shop/Shipments',
            icon: 'fa fa-shopping-cart',
            type: [0 , 2]
          },
          {
            name: 'Cancellation',
            url: '/shop/Cancellations',
            icon: 'fa fa-exclamation-circle',
            type: [0 , 2 , 1]
          },
          // {
          //   name: 'Category(s)',
          //   url: '/shop/category',
          //   icon: 'fa fa-code'
          // },
          { 
            name: 'Comment(s)',
            url: '/shop/Comments',
            icon: 'fa fa-comments',
            type: [0 , 2]
            
          },
          // {
          //   name: 'Rating(s)',
          //   url: '/references/categories',
          //   icon: 'fa fa-star-o'
          // },
          {
            name: 'History Log(s)',
            url: '/shop/Logs',
            icon: 'fa fa-database',
            type: [2]
          },
        ]},
        // {
        //   name: 'Settings',
        //   url: '/settings',
        //   icon: 'fa fa-cogs',
        //   children:[
        //     {
        //       name: 'Profile',
        //       url: '/settings/Profile',
        //       icon: 'icon-list'
        //     },
        //     {
        //       name: 'Logout',
        //       url: '/logout',
        //       icon: 'icon-list'
        //     },
        //   ]},
        // {
        //   name: 'Finance',
        //   url: '/references',
        //   icon: 'fa fa-usd',
        //   children:[
        //     {
        //       name: 'Income',
        //       url: '/references/categories',
        //       icon: 'fa fa-credit-card'
        //     },
        // ]},
            // {
            //   name: 'Settings',
            //   url: '/references',
            //   icon: 'fa fa-cogs',
            //   children:[
            //     {
            //       name: 'Address',
            //       url: '/references/categories',
            //       icon: 'fa fa-address-book-o'
            //     },
        
            //   ]},
  ],
}

