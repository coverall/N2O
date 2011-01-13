// $Id: pngfix.js,v 1.2 2006/10/19 00:53:46 patrick Exp $
if (window.attachEvent)
{
	window.attachEvent("onload", initIE);
	window.attachEvent("onunload", function()
	{
		var clearElementProps = [
			'data',
			'onmouseover',
			'onmouseout',
			'onmousedown',
			'onmouseup',
			'ondblclick',
			'onclick',
			'onselectstart',
			'oncontextmenu'
		];
	
		var el;
		for (var d = document.all.length; d--;)
		{
			el = document.all[d];
			for (var c = clearElementProps.length; c--;)
			{
				el[clearElementProps[c]] = null;
			}
		}
	});
}


//-------------------------------------------------------------------------
// FUNCTION: initIE()
//-------------------------------------------------------------------------
// IE doesn't support 8-bit alpha channels for PNG images, but there is a
// work-around using DirectX. initIE() goes through each <img> or
// <input type="image"> where the src is a .png, and applies a special
// style that activates a DirectX alpha filter. It also goes through every
// stylesheet looking for background-image styles that use .pngs, and applies
// the appropriate DirectX filter.
//
function initIE()
{
	var allImages = document.getElementsByTagName("img");
	var _pngTest = new RegExp(".png$", "i");
	
	for (i = 0; i < allImages.length; i++)
	{
		if (_pngTest.test(allImages[i].src))
		{
			fixImage(allImages[i]);
		}
	}

	var allImages = document.getElementsByTagName("input");
	
	for (i = 0; i < allImages.length; i++)
	{
		if ((allImages[i].type == "image") && _pngTest.test(allImages[i].src))
		{
			fixImage(allImages[i]);
		}
	}
	
	/*for (i = 0; i < document.styleSheets.length; i++)
	{
		var sheet = document.styleSheets[i];
		
		if (sheet.media != "print")
		{
			for (j = 0; j < sheet.rules.length; j++)
			{
				var rule = sheet.rules[j];
				
				if (_pngTest.test(rule.style.backgroundImage))
				{
					var mypng = rule.style.backgroundImage.substring(5, rule.style.backgroundImage.length - 1);
					
					// finish!
				}
			}
		}
	}*/
}

function fixImage(image)
{
	if (image.srcElement != null)
	{
		var theImage = image.srcElement;
	}
	else
	{
		var theImage = image;
	}

	if (theImage.src != "/images/spacer.gif")
	{
		theImage.detachEvent("onpropertychange", fixImage);
		theImage.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + theImage.src + "',sizingMethod='crop')";
		theImage.style.width = theImage.width + "px";
		theImage.style.height = theImage.height + "px";
		theImage.src = "/images/spacer.gif";
		theImage.attachEvent("onpropertychange", fixImage);
	}
}

