/**
 * SqueezeBox - Expandable Lightbox
 * 
 * Allows to open various content as modal,
 * centered and animated box.
 * 
 * Dependencies: MooTools 1.2+ (09/2007)
 * 
 * Inspired by 
 *  ... Lokesh Dhakar	- The original Lightbox v2
 * 
 * @version		1.0rc2
 * 
 * @license		MIT-style license
 * @author		Harald Kirschner <mail [at] digitarald.de>
 * @copyright	Author
 */
var SqueezeBox = {

	presets: {
		size: {x: 600, y: 450},
		sizeLoading: {x: 200, y: 150},
		marginInner: {x: 20, y: 20},
		marginImage: {x: 150, y: 200},
		handler: false,
		target: null,
		closeWithOverlay: true,
		zIndex: 65000,
		overlayOpacity: 0.7,
		classWindow: '',
		classOverlay: '',
		disableFx: false,
		onOpen: $empty,
		onClose: $empty,
		onUpdate: $empty,
		onResize: $empty,
		onMove: $empty,
		onShow: $empty,
		onHide: $empty,
		fxOverlayDuration: 250,
		fxResizeDuration: 750,
		fxResizeTransition: Fx.Transitions.Quint.easeOut,
		fxContentDuration: 250,
		ajaxOptions: {}
	},

	initialize: function(options) {
		if (this.options) return this;
		this.presets = $merge(this.presets, options);
		this.setOptions(this.presets).build();
		this.listeners = {
			window: this.reposition.bind(this, [null]),
			close: this.close.bind(this),
			key: this.onKey.bind(this)
		};
		this.isOpen = this.isLoading = false;
		return this;
	},

	build: function() {
		this.content = new Element('div', {id: 'sbox-content'});
		this.btnClose = new Element('a', {id: 'sbox-btn-close', href: '#'});
		this.overlay = new Element('div', {
			id: 'sbox-overlay',
			styles: {display: 'none', zIndex: this.options.zIndex}
		}).inject(document.body);
		this.win = new Element('div', {
			id: 'sbox-window',
			styles: {display: 'none', zIndex: this.options.zIndex + 2}
		}).adopt(this.btnClose, this.content).inject(document.body);
		this.fx = {
			overlay: new Fx.Style(this.overlay, 'opacity', {
				duration: this.options.fxOverlayDuration,
				wait: false
			}).set(0),
			win: new Fx.Styles(this.win, {
				duration: this.options.fxResizeDuration,
				transition: this.options.fxResizeTransition,
				wait: false,
				unit: 'px'
			}),
			content: new Fx.Style(this.content, 'opacity', {
				duration: this.options.fxContentDuration,
				wait: false
			}).set(0)
		};
	},

	addClick: function(el) {
		return el.addEvent('click', function() {
			return !SqueezeBox.fromElement(this);
		});
	},

	fromElement: function(el, options) {
		this.initialize();
		this.element = $(el);
		this.setOptions($merge(this.presets, options || {}, (this.element && this.element.rel) ? Json.evaluate(this.element.rel) : {}));
		this.assignOptions();
		this.url = ((this.element) ? (this.options.url || this.element.getProperty('href')) : el) || '';
		if (this.options.handler) {
			var handler = this.options.handler;
			return this.setContent(handler, this.parsers[handler].call(this, true));
		}
		var res;
		for (var key in this.parsers) {
			if ((res = this.parsers[key].call(this))) return this.setContent(key, res);
		}
		return false;
	},

	assignOptions: function() {
		this.overlay.setProperty('class', this.options.classOverlay);
		this.win.setProperty('class', this.options.classWindow);
		if (Client.Engine.ie6) this.win.addClass('sbox-window-ie6');
	},

	close: function(e) {
		if (e) new Event(e).stop();
		if (!this.isOpen) return this;
		this.fx.overlay.start(0).chain(this.toggleOverlay.bind(this));
		this.win.setStyle('display', 'none');
		this.trashImage();
		this.toggleListeners();
		this.isOpen = null;
		this.fireEvent('onClose', [this.content]).removeEvents();
		this.options = {};
		this.setOptions(this.presets).callChain();
		return this;
	},

	onError: function() {
		if (this.image) this.trashImage();
		this.setContent('Error during loading');
	},

	trashImage: function() {
		if (this.image) this.image = this.image.onload = this.image.onerror = this.image.onabort = null;
	},

	setContent: function(handler, content) {
		if (!this.handlers[handler]) return false;
		this.content.setProperty('class', 'sbox-content-' + handler);
		this.applyTimer = this.applyContent.delay(this.fx.overlay.options.duration, this, this.handlers[handler].call(this, content));
		if (this.overlay.$attributes.opacity) return this;
		this.toggleOverlay(true);
		this.fx.overlay.start(this.options.overlayOpacity);
		this.reposition();
		return this;
	},

	applyContent: function(content, size) {
		this.applyTimer = $clear(this.applyTimer);
		this.hideContent();
		if (!content) this.toggleLoading(true);
		else {
			if (this.isLoading) this.toggleLoading(false);
			this.fireEvent('onUpdate', [this.content], 20);
		}
		this.content.empty()[['string', 'array', false].contains($type(content)) ? 'setHTML' : 'adopt'](content || '');
		this.callChain();
		if (!this.isOpen) {
			this.toggleListeners(true);
			this.resize(size, true);
			this.isOpen = true;
			this.fireEvent('onOpen', [this.content]);
		} else this.resize(size);
	},

	resize: function(size, instantly) {
		var sizes = window.getSize();
		this.size = $merge(this.isLoading ? this.options.sizeLoading : this.options.size, size);
		var to = {
			width: this.size.x, height: this.size.y,
			left: (sizes.scroll.x + (sizes.size.x - this.size.x - this.options.marginInner.x) / 2).toInt(),
			top: document.body.clientHeight*0.04
		};
		$clear(this.showTimer || null);
		this.hideContent();
		if (!instantly) {
			this.fx.win.start(to).chain(this.showContent.bind(this));
		} else {
			this.win.setStyles(to).setStyle('display', '');
			this.showTimer = this.showContent.delay(50, this);
		}
		return this.reposition(sizes);
	},

	toggleListeners: function(state) {
		var task = state ? 'addEvent' : 'removeEvent';
		this.btnClose[task]('click', this.listeners.close);
		if (this.options.closeWithOverlay) this.overlay[task]('click', this.listeners.close);
		document[task]('keydown', this.listeners.key);
		window[task]('resize', this.listeners.window);
		window[task]('scroll', this.listeners.window);
	},

	toggleLoading: function(state) {
		this.isLoading = state;
		this.win[state ? 'addClass' : 'removeClass']('sbox-loading');
		if (state) this.fireEvent('onLoading', [this.win]);
	},

	toggleOverlay: function(state) {
		this.overlay.setStyle('display', state ? '' : 'none');
		document.body[state ? 'addClass' : 'removeClass']('body-overlayed');
	},

	showContent: function() {
		if (this.content.$attributes.opacity) this.fireEvent('onShow', [this.win]);
		this.fx.content.start(1);
	},

	hideContent: function() {
		if (!this.content.$attributes.opacity) this.fireEvent('onHide', [this.win]);
		this.fx.content.set(0);
	},

	onKey: function(e) {
		switch (e.key) {
			case 'esc':
				this.close();
				break;
		}
	},

	reposition: function(sizes) {
		sizes = sizes || window.getSize();
		this.overlay.setStyles({
			left: sizes.scroll.x, top: sizes.scroll.y,
			width: sizes.size.x, height: sizes.size.y
		});
		this.win.setStyles({
			left: (sizes.scroll.x + (sizes.size.x - this.win.offsetWidth) / 2).toInt(),
			top: document.body.clientHeight*0.04
		});
		return this.fireEvent('onMove', [this.overlay, this.win, sizes]);
	},

	removeEvents: function(type){
		if (!this.$events) return this;
		if (!type) this.$events = null;
		else if (this.$events[type]) this.$events[type] = null;
		return this;
	},

	parsers: {
		'image': function(preset) {
			return (preset || this.url.test(/\.(jpg|jpeg|png|gif|bmp)$/i)) ? this.url : false;
		},
		'clone': function(preset) {
			if ($(this.options.target)) return $(this.options.target);
			if (preset || (this.element && !this.element.parentNode)) return this.element;
			var bits = this.url.match(/#([\w-]+)$/);
			return (bits) ? $(bits[1]) : false;
		},
		'url': function(preset) {
			return (preset || (this.url && !this.url.test(/^javascript:/i))) ? this.url: false;
		},
		'iframe': function(preset) {
			return (preset || this.url) ? this.url : false;
		},
		'string': function(preset) {
			return true;
		}
	},

	handlers: {
		'image': function(url) {
			var size, tmp = new Image();
			this.image = null;
			tmp.onload = tmp.onabort = tmp.onerror = (function() {
				tmp.onload = tmp.onabort = tmp.onerror = null;
				if (!tmp.width) {
					this.onError.delay(10, this);
					return;
				}
				var win = {x: window.getWidth() - this.options.marginImage.x, y: window.getHeight() - this.options.marginImage.y};
				size = {x: tmp.width, y: tmp.height};
				for (var i = 2; i--;) {
					if (size.x > win.x) {
						size.y *= win.x / size.x;
						size.x = win.x;
					} else if (size.y > win.y) {
						size.x *= win.y / size.y;
						size.y = win.y;
					}
				}
				size.x = size.x.toInt();
				size.y = size.y.toInt();
				this.image = (Client.Engine.webkit419) ? new Element('img', {src: this.image.src}) : $(tmp);
				tmp = null;
				this.image.setProperties({width: size.x, height: size.y});
				if (this.isOpen) this.applyContent(this.image, size);
			}).bind(this);
			tmp.src = url;
			if (tmp && tmp.onload && tmp.complete) tmp.onload();
			return (this.image) ? [this.image, size] : null;
		},
		'clone': function(el) {
			return el.clone();
		},
		'adopt': function(el) {
			return el;
		},
		'url': function(url) {
			this.ajax = new Ajax(url, this.options.ajaxOptions);
			this.ajax.addEvents({
				'onSuccess': function(resp) {
					this.applyContent(resp);
					this.ajax = null;
				}.bind(this),
				'onFailure': this.onError.bind(this)
			}).request.delay(10, this.ajax);
		},
		'iframe': function(url) {
			return new Element('iframe', {
				src: url,
				frameBorder: 0,
				width: this.options.size.x,
				height: this.options.size.y
			});
		},
		'string': function(str) {
			return str;
		}
	},

	extend: $extend
};

SqueezeBox.parsers.adopt = SqueezeBox.parsers.clone;

SqueezeBox.extend(Events.prototype).extend(Options.prototype).extend(Chain.prototype);