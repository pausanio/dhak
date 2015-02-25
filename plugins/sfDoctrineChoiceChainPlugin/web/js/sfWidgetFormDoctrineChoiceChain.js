if(typeof Array.prototype.clone == 'undefined'){
	Array.prototype.clone = function(){
		var a = [];
		for(var i = 0; i < this.length; i++){
			a[i] = this[i];
		}
		return a;
	};
}
if(typeof Array.prototype.last == 'undefined'){
    Array.prototype.last = function(){
        return this[this.length - 1];
    }
}

var sfWidgetFormChoiceChain = {};
sfWidgetFormChoiceChain.get_item_num = function(item){
	var c = item.className.split(' ');
	var num = null;
	$(c).each(function(){
		if(this.indexOf('item-') != -1){
			num = this.split('-')[1];
			return true;
		}
	});
	return num;
};

sfWidgetFormChoiceChain.settings = {
    ITEM_CLASS: 'sf_choice_chain_item',
    ITEM_CLASS_CHAIN: 'sf_choice_chain_chain',
    ITEM_CLASS_URL: 'sf_choice_chain_url',
    ITEM_CLASS_MODEL: 'sf_choice_chain_item_model',
    ITEM_CLASS_NUM: 'sf_choice_chain_item_num'
};
sfWidgetFormChoiceChain.items = {};

sfWidgetFormChoiceChain.init = function($){
    var oThis = this;
    var $items = $('.' + this.settings.ITEM_CLASS);
    $items.each(function(){
        var expl = this.className.split(' ');
        var num = expl.last().split('-').last();
        this._num = num;

        if(typeof oThis.items[num] == 'undefined'){
            oThis.items[num] = {
                items: [],
                chain: '',
                url: ''
            };
        }
        if($(this).hasClass(oThis.settings.ITEM_CLASS_CHAIN)){
            oThis.items[num].chain = $(this).val();
        }else if($(this).hasClass(oThis.settings.ITEM_CLASS_URL)){
            oThis.items[num].url = $(this).val();
        }else{
            oThis.init_select(this);
            oThis.items[num].items.push(this);
        }
    });
};
sfWidgetFormChoiceChain.init_select = function(select){
    select._model = this._get_model(select);
    select._chain_num = this._get_chain_num(select);
    $(select).change(function(){
        if(this._chain_num == sfWidgetFormChoiceChain.items[this._num].items.length){
            return true;
        }
        if(this.value == ''){
                sfWidgetFormChoiceChain.set_empty_after(this._num, this._chain_num);
                return;
        }
        var params = {};
        params['sf_choice_chain[num]'] = this._chain_num - 1;
        params['sf_choice_chain[value]'] = this.value;
        params['sf_choice_chain[chain]'] = sfWidgetFormChoiceChain.items[this._num].chain;
        var url = sfWidgetFormChoiceChain.items[this._num].url;

        var oSelect = this;
        $.post(url, params, function(data){
                if(data){
                        //alert(data);
                    sfWidgetFormChoiceChain.set_data_after(oSelect._num, oSelect._chain_num, data);
                }else{
                //	alert(data);
                }
        });
    });
};
sfWidgetFormChoiceChain._get_model = function(select){
    var expl = select.className.split(' ');
    var oThis = this;
    for(var i = 0; i < expl.length; i++){
        if(expl[i].indexOf(oThis.settings.ITEM_CLASS_MODEL) != -1){
            return expl[i].split('-').last();
        }
    }
    return null;
};
sfWidgetFormChoiceChain._get_chain_num = function(select){
    var expl = select.className.split(' ');
    var oThis = this;
    for(var i = 0; i < expl.length; i++){
        if(expl[i].indexOf(oThis.settings.ITEM_CLASS_NUM) != -1){
            return Number(expl[i].split('-').last());
        }
    }
    return null;
};

sfWidgetFormChoiceChain.set_empty_after = function(num, chain_num)
{
    var items = this.items[num].items;
    var item = null;
    for(var i = 0; i < items.length; i++){
        if(items[i]._chain_num > chain_num){
            this.set_empty(items[i]);
        }
    }
};
sfWidgetFormChoiceChain.set_empty = function(item)
{    
    var empty = this.get_option(item._num, 'add_empty', item._chain_num) || '';
    $(item).html('<option value="">' + empty + '</option>');
};
sfWidgetFormChoiceChain.set_data_after = function(num, chain_num, data){
    var items = this.items[num].items;
    //alert(items);
    var select = null;
    for(var i = 0; i < items.length; i++){
        if(items[i]._chain_num == chain_num + 1){
            select = items[i];
            break;
        }
    }
    if(select){
        $(select).html(data);
        this.set_empty_after(select._num, select._chain_num);
        if($(select).val()){
            $(select).change();
        }
    }
};

sfWidgetFormChoiceChain.get_option = function(num, option_name, chain_num){
    if(sfWidgetFormChoiceChainOptions[num]){
        var option = sfWidgetFormChoiceChainOptions[num][option_name];
        if(chain_num){
            return option[chain_num];
        }
        return option;
    }
    return false;
};


jQuery(function($){
    sfWidgetFormChoiceChain.init($);
});