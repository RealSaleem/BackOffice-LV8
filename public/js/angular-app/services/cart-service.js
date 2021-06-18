/*
* 
*/

(function(){
    'use strict';
    angular
      .module('posApp')
      .factory('cartService', cartService);

      cartService.$inject = ['localStorageService', '$filter'];
      function cartService(localStorageService, $filter){

        return {
            init : init,
            update : update,
            addItem : addItem,
            removeItem: removeItem,
            flush : flush,
            addPayment : addPayment,
            getBalance : getBalance,
            initializeCart : initializeCart
        }

        function _generateIdentity(){
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for( var i=0; i < 6; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }


        /*
        * Initialize empty cart with default values and returns the created object
        */
        function initializeCart(){
            return {
                items               : [], 
                total               : 0.0,
                custom_price        : 0.0, 
                notes               : '', 
                discount            : 0.0,
                sub_total           : 0.0,
                discount_percentage : 0,
                discount_amount     : 0,
                discounted_amount   : 0,
                payments            : [],
                total_items         : 0,
                status              : '',
                return_order_id     : 0,
                customer_id         : 0,
                table_no         : '',
                print         : '',
                waiter         : '',
                identity            : _generateIdentity()
            };
        }

        /*
        * Add payment into cart payments
        */
        function addPayment(cart, type, amount){
            cart.payments.push({ type : type , amount : amount });
            return getBalance(cart);
        }

        /*
        * returns cart balance
        */
        function getBalance(cart){
            var balance = cart.total;
            angular.forEach(cart.payments, function(p){
                balance -= p.amount;
            });
            return balance;
        }

        /*
        * Discard the whole cart
        */
        function flush(cart){
            cart = null;
            localStorageService.set('cart', null);
        }

        /*
        * initiate cart, from local storage if exist 
        * if not exits in local storage initiate with
        * default values
        */
        function init(){
            return  localStorageService.get('cart') || initializeCart();
        }

        /*
        * add new item in cart with product and variant
        */
        function addItem(cart, variant, product){
            // console.log(variant);
            variant.attribute_value_1 = variant.attribute_value_1.replace(/\s+/, "");
            variant.attribute_value_2 = variant.attribute_value_2.replace(/\s+/, "");
            variant.attribute_value_3 = variant.attribute_value_3.replace(/\s+/, "");
            let description = product.name;
            if (variant.attribute_value_1 != null && variant.attribute_value_1.length > 0) {
                description += '/' + variant.attribute_value_1;
            }

            if (variant.attribute_value_2 != null && variant.attribute_value_2.length > 0) {
                description += '/' + variant.attribute_value_2;
            }

            if (variant.attribute_value_3 != null && variant.attribute_value_3.length > 0) {
                description += '/' + variant.attribute_value_3;
            }

            for (var i = 0; i < cart.items.length; i++) {
                
                if (cart.items[i].variant.id == variant.id && cart.items[i].variant.product_id == variant.product_id) {
                    cart.items[i].quantity = cart.items[i].quantity + 1;
                    return;
                }
            }

            cart.items.push({
                //product_variant object 
                variant                 : variant, 
                //product object
                product                 : product, 
                quantity                : 1,
                //total price of item
                price                   : variant.retail_price,
                discount_percentage     : 0.0,
                discount_amount         : 0.0,
                discounted_amount       : 0.0,
                //discount_product        : 0.0,
                markup                  : 0.0,
                //binded with inut box under item in cart
                custom_price            : variant.retail_price,
                markup_percentage       : 0,
                notes                   : '',
                print                    :false,
                //unique id for view operations, like delete add edit etc
                id: Math.random(),
                description: description
            });

        }

        /*
        * Removes the item => i from cart
        */
        function removeItem(cart, i){
            var index = cart.items.findIndex(function(item){
                return (item.id == i.id);
            });
            cart.items.splice(index, 1);
        }

        /*
        * This function recalculates cart,
        * called upon update/delete or addition of item in cart
        */
        var flag = false;
        var remaining_rewards = 0;
        function update(cart, oldCart){
            // console.log('update cart',cart);
            // flag is set to true when cart is updated 
            // in response of view update, 
            // since this function is being called from watcher
            // it gets into infinite loop, to prevent that we set this
            // flag to true whenever we make changes to cart
            // if(flag){
            //     flag = false;
            //     return;
            // }

            var cart_sub_total = 0.0;
            
            cart.total_items = 0;

            
            angular.forEach(cart.items, function(item){
                //item before update, if new item, assign same item
                var oldItem = $filter('filter')(oldCart.items, {id : item.id}, true)[0] || item;

                var quantity = item.quantity || 0;
                cart.total_items += quantity;
                //if price has been updated
                
                // if (item.custom_price<=parseFloat(item.variant.supplier_price)) {
                //     console.log('stock_cost');
                // }
                if(item.is_amount){
                    item.discount_percentage = (item.discount_amount * 100)/item.variant.retail_price;
                }

                if(item.custom_price == null){
                    item.custom_price = parseFloat(item.retail_price);
                }

                if(item.discount_percentage == null){
                    item.discount_percentage = 0;
                }

                if(item.custom_price != oldItem.custom_price){
                    item.markup = 0.0;
                    item.discount_percentage = 0.0;
                    if(item.variant.retail_price < item.custom_price){
                        var increased_price = ( item.custom_price - item.variant.retail_price );
                        item.markup = ((increased_price/item.variant.retail_price) * 100).toFixed(2);
                    }else if(item.variant.retail_price > item.custom_price){
                        var decreased_price = ( item.variant.retail_price - item.custom_price)
                        item.discount_percentage = (( decreased_price / item.variant.retail_price) * 100).toFixed(2);
                    }                    
                }
                // if discount is updated, calculate custom price again
                else if(item.discount_percentage != oldItem.discount_percentage){
                    item.discounted_amount = (item.variant.retail_price * (item.discount_percentage / 100));
                    item.custom_price = parseFloat(item.variant.retail_price);

                    if(item.discounted_amount > 0){
                        item.custom_price -= parseFloat(item.discounted_amount);
                    }else{
                        item.discounted_amount = 0;
                    }
                }
                // if discount product is updated, calculate custom price again
                /*if(item.discount_product != oldItem.discount_product){
                    item.discounted_amount =  item.discount_product;
                    item.custom_price = parseFloat(item.variant.retail_price) - parseFloat(item.discounted_amount);

                }*/
                
                if (parseFloat(item.custom_price)<parseFloat(item.variant.supplier_price)) {
                    toastr.error('Too much discount given');
                }


                
                item.discounted_amount = (parseFloat(item.variant.retail_price) * (parseFloat(item.discount_percentage) / 100));

                if(!(item.discounted_amount  > -1)){
                    item.discounted_amount = 0;
                }

                if(!(item.discount_percentage > -1)){
                    item.discount_percentage = 0;
                }                                
                

                //total price of item
                let price = (quantity * item.custom_price);

                //parse to numberic, string number comparison 
                // won't work as expected
                item.variant.retail_price = parseFloat(item.variant.retail_price);
                item.price = parseFloat(price);
                cart_sub_total += parseFloat(item.price);
            });

            if (cart.discount_percent==true) {    
                cart.discounted_amount = (cart_sub_total * (cart.discount_percentage / 100));

                if (parseFloat(cart.discount_percentage)>parseFloat(cart.total)) {
                    toastr.error('Too much discount given');
                }
                cart.sub_total = cart_sub_total ;
                cart.total = cart_sub_total - cart.discounted_amount;
            }

            if (cart.discount_percent==false) {
                cart.total = cart_sub_total - cart.discount_amount;

                if (parseFloat(cart.discount_amount)>parseFloat(cart.total)) {
                    toastr.error('Too much discount given');
                }
                cart.sub_total = cart_sub_total ;
                cart.discounted_amount =  cart.discount_amount;
            }

            if (cart.discount_percent==undefined) {
                cart.total = cart_sub_total;
                cart.sub_total = cart_sub_total ;
                cart.discounted_amount =  cart.discount_amount;
                // flag = false;
                // return;
            }

            if (cart.applyReward == true) {    
                // if (parseFloat(cart.reward_value) > parseFloat(oldCart.reward_value)) {
                //     toastr.error('Too much reward given');
                // }else{

                // }
                cart.sub_total = cart_sub_total ;
                cart.total = cart_sub_total - cart.reward_value;
            }
            if(cart.customer  != undefined && cart.customer_id > 0 ){
                if(cart.customer.reward_points >= 0){
                    // console.log('$scope.reward_points1',cart.reward_points1);
                    remaining_rewards = cart.reward_points - (cart.reward_value / cart.cap_amount);
                    cart.customer.reward_points = remaining_rewards + (cart.total / cart.cap_amount);

                    // console.log('remaining rewards',cart.customer.reward_points);

                }
            }

            if(flag){
                flag = false;
                return;
            }

            //update balance
            cart.balance = getBalance(cart);
            localStorageService.set('cart', cart);
            flag = true;
        }
      }
})();