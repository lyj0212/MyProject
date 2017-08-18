TrexConfig.addTool("emoticon2", {wysiwygonly: true, status:true});

Trex.Tool.Emoticon2 = Trex.Class.create({
	$const: {
		__Identity: 'emoticon2'
	},
	$extend: Trex.Tool,
	oninitialized: function(){
		var _canvas = this.canvas;
		var button = new Trex.Button(this.buttonCfg);
		var menu = new Trex.Menu.Emoticon2(this.menuCfg);
		var executeHandler = function(html){
			_canvas.pasteContent(html);
		};
		this.weave.bind(this)(
			button,
			menu,
			executeHandler
		);
	}
});

Trex.Menu.Emoticon2 = Trex.Class.create({
	$extend: Trex.Menu,
	$mixins: [Trex.I.JSRequester],
	ongenerated: function(config) {
		var _menu = this;
		var _template = new Template('<img src="#{src}" align="middle" width="70" class="_editor_emoticon" style="cursor:pointer" />');
		var _html = [];

		for(var i=1; i<=183; i++)
		{
			_html.push(_template.evaluate({src : '/_res/img/kakaofriends/' + i + '.png'}));
		}

		_menu.elMenu.innerHTML = _html.join("");

		var imgs = $tom.collectAll( _menu.elMenu, "img" );
		for( var i = 0; i < imgs.length;  i++ ){
			$tx.observe( imgs[i], "click", function(index){
				return function(ev){
					_menu.onSelect(ev, $(_html[index]).removeAttr('width').css({cursor:'auto', maxWidth:'130px'}).prop('outerHTML'));
				}
			}(i));
		}
	},
	onregenerated: function(config) {
	}
});