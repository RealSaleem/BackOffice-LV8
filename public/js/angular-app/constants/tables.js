"use strict";
(function(){
	var _product, _category, _productVariant, 
		_productTag, _register, _registerHistoy,
		_compositeProduct, _customer, _order, _orderItems, 
		_tag, _suppliers, _user;

	posApp.constant('TABLE',{
		Product : { name : 'products', columns : [], fk : [] },
		ProductVariant : { name : 'products', columns : [] },
		ProductTag : { name : 'products', columns : [] },
		Register : {name : 'products', columns : []},
		RegisterHistory : {name : 'products', columns : []},
		CompositeProduct : {name : 'products', columns : []},
		Category : {name : 'products', columns : []},
		Customer : {name : 'products', columns : []},
		Order : {name : 'products', columns : []},
		OrderItem : {name : 'products', columns : []},
		Tag : {name : 'products', columns : []},
		Suppliers : {name : 'products', columns : []},
		User : {name : 'products', columns : []}
	 });

})();