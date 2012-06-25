/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward
 * @filesource
 * @licence LGPL
 */

var News4ward = {

	setStatus: function(el,id,status)
	{
		var img = el.getElement('img');
		el.getParent().getPrevious('a img').set('src',img.get('src')).set('title',img.get('title'));

		new Request.Contao({
			onRequest: AjaxRequest.displayBox('…'),
			onSuccess: function()
			{
				AjaxRequest.hideBox();
				News4ward.hideTogglers();
   			}
		}).post({'action':'news4wardArticleStatusToggle', 'id':id, 'status':status, 'REQUEST_TOKEN':REQUEST_TOKEN});

 	},

	showStatusToggler: function(el,id)
	{
		News4ward.mask.show();

		var togglerContainer = el.getNext('.news4wardStatusToggler');
		togglerContainer.setPosition({x: window.event.x + 10, y: window.event.y - togglerContainer.getDimensions().y/2});
		togglerContainer.setStyle('display','block').set('tween',{'duration':300}).fade('hide').fade('in');
	},

	hideTogglers: function()
	{
		News4ward.mask.hide();
		document.getElements('.news4wardStatusToggler').fade('out');
	}
};

window.addEvent('domready',function(){
	News4ward.mask = new Mask(document.body,
	{
		'onClick': News4ward.hideTogglers
	});
});
