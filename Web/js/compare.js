function TwoFace(id, width, height) {
if (!(this instanceof TwoFace)) {
	return new TwoFace(id, width, height);
}

var canvas = document.createElement('canvas'),
container = document.getElementById(id),
divide = 0.5;

this.ctx = canvas.getContext('2d');
this.images = [];

 // Event handlers
 canvas.addEventListener('mousemove', handler, false);
 canvas.addEventListener('mousedown', handler, false);
 canvas.addEventListener('mouseup', handler, false);

 canvas.addEventListener('touchmove', handler, false);
 canvas.addEventListener('touchstart', handler, false);
 canvas.addEventListener('touchend', handler, false);

 var self = this;

 function handler(ev) {
     if (ev.layerX || ev.layerX == 0) { // Firefox
     	ev._x = ev.layerX;
     	ev._y = ev.layerY;
     } else if (ev.offsetX || ev.offsetX == 0) { // Opera
     	ev._x = ev.offsetX;
     	ev._y = ev.offsetY;
     }

     var eventHandler = self[ev.type];
     if (typeof eventHandler == 'function') {
     	eventHandler.call(self, ev, id);
     }
  }

 // Draw canvas into its container
 canvas.setAttribute('width', width);
 canvas.setAttribute('height', height);
 container.appendChild(canvas);

 Object.defineProperty(this, 'ready', {
 	get: function() {
 		return this.images.length >= 2;
 	}
 });

 Object.defineProperty(this, 'width', {
 	get: function() {
 		return width;
 	}
 });

 Object.defineProperty(this, 'height', {
 	get: function() {
 		return height;
 	}
 });

 Object.defineProperty(this, 'divide', {
 	get: function() {
 		return divide;
 	},
 	set: function(value) {
 		if (value > 1) {
 			value = (value / 100);
 		}

 		divide = value;
 		this.draw();
 	}
 });
}




TwoFace.prototype = {
	add: function(srcBefore, srcAfter) {
		var imgBefore = createImage(srcBefore, onload.bind(this));
		var imgAfter = createImage(srcAfter, onload.bind(this));

		function onload(event) {
			this.images.push(imgBefore);
			this.images.push(imgAfter);

			if (this.ready) {
				this.draw();
			}
		}
	},

	draw: function() {
		if (!this.ready) {
			return;
		}

		var lastIndex = this.images.length - 1,
		before = this.images[lastIndex - 1],
		after = this.images[lastIndex];

		this.drawImages(this.ctx, before, after);
		this.drawHandle(this.ctx);
	},

	drawImages: function(ctx, before, after) {
		var split = this.divide * this.width;

		ctx.drawImage(after, 0, 0);

		ctx.fillStyle = "white";
		ctx.font = "bold 20px Arial";
		if(split <= this.width-60) {	
			ctx.fillText("After", this.width-60, this.height-25);
		}
		else if (split <= this.width-30) {
			ctx.fillText("A", this.width-30, this.height-25);
		}

		ctx.drawImage(before, 0, 0, split, this.height, 0, 0, split, this.height);

		ctx.fillStyle = "white";
		ctx.font = "bold 20px";
		if(split >= 80) {	
			ctx.fillText("Before", 10, this.height-25);
		}
		else if (split < 80 && split > 25) {
			ctx.fillText("B", 10, this.height-25);
		}
	},

	drawHandle: function(ctx) {
		var split = this.divide * this.width; 

		ctx.fillStyle = "white"
		ctx.fillRect(split - 1, 0, 1, this.height);

		ctx.fillStyle = "black";
		ctx.fillRect(split - 23, this.height/1.1-19, 46, 24);

		ctx.fillStyle="white";
		ctx.beginPath();
		ctx.moveTo(split-10,this.height/1.1-14);
		ctx.lineTo(split-20,this.height/1.1-7);
		ctx.lineTo(split-10,this.height/1.1);
		ctx.closePath();
		ctx.fill();

		ctx.beginPath();
		ctx.moveTo(split+10,this.height/1.1-14);
		ctx.lineTo(split+20,this.height/1.1-7);
		ctx.lineTo(split+10,this.height/1.1);
		ctx.closePath();
		ctx.fill();
	},

	mousedown: function(event, id) {
		var divide = event._x / this.width;
		this.divide = divide;

		this.dragstart = true;
	},

	mousemove: function(event, id) {
		if (this.dragstart === true) {
			var divide = event._x / this.width;
			this.divide = divide;
		}
	},

	mouseup: function(event, id) {
		var divide = event._x / this.width;
		this.divide = divide;

		this.dragstart = false;
	},

	touchstart: function(event, id) {
		var rect = document.getElementById(id).getBoundingClientRect();
		var divide = (event.targetTouches[0].clientX - rect.left) / this.width; 
		this.divide = divide;

		this.dragstart = true;
	},

	touchmove: function(event, id) {
		if (this.dragstart === true) {
			var rect = document.getElementById(id).getBoundingClientRect();
			var divide = (event.targetTouches[0].clientX - rect.left) / this.width;
			this.divide = divide;
		}
	},

	touchend: function(event, id) {
		var rect = document.getElementById(id).getBoundingClientRect();
		var divide = (event.changedTouches[0].clientX - rect.left) / this.width;
		this.divide = divide;

		this.dragstart = false;
	}
};

function createImage(src, onload) {
	var img = document.createElement('img');
	img.src = src;

	if (typeof onload == 'function') {
		img.addEventListener('load', onload);
	}

	return img;
}