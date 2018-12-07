# OSCDraw

OSCDraw is a HTML/Javascript based software to generate stereo audio files suitable for drawing images on oscilloscopes using XY-mode.

## Why

Back in january of 2018 [Neil Fraser](https://neil.fraser.name/) published [JS Oscilloscope](https://neil.fraser.name/news/2018/01/25/)
 and [JS Oscilloscope Clock](https://neil.fraser.name/news/2018/clock.html), which implemented the aforementioned audio generation. While it was nice to watch his version with animation didn't quite fit my need to display a static logo. Since other tools didn't work for me either and his code was pretty simple I used it as a starting point for this tool.
 
## How

First you need to connect your oscilloscope to a suitable computer/laptop/smartphone/whatever. Ideal would be a cable with stereo headphone jack (TRRS) on one side and two BNC connectors on the other. DIY-cables of yourse work too, frequency isn't that high. Plug the right audio channel (usually red) into the scopes X-axis (usually channel 1) and the left audio channel (usually white) into the scopes Y-Axis (usually channel 2).
Now set your scope to XY-mode (mostly found near timebase control or display options) and start playing around.
For drawings like this analog scopes will show a much nicer image than digital ones.

To open the page either save [oscdraw.html](https://raw.githubusercontent.com/adlerweb/OSCDraw/master/oscdraw.html) to your pc and open in your favorite (but current) browser or just [use it online](https://adlerweb.github.io/OSCDraw/oscdraw.html).

### Drawing

Open the page, move your cursor over the large square, press and hold your left mouse button and scribble away. Simple, huh?

If you are using different shapes try to keep end and start points as close as possible to avoid spurious lines. The scope uses a continuous beam, if you stop a shape on the left side and start drawing something else on the right it must move the beam across the whole screen. While this happens pretty fast it still can be visible as faint lines. Same goes for start- and endpoint of a single line drawing.

The more things you draw the more flicker you'll get. Soundcards aren't exactly designed to be fast or accurate, so the maximum refresh rate and resolution is somewhat limited.

Using a lower sample rate might get a more stable and clean image but will show more flicker.

You can use Clear to start over with a blank canvas.

Using the textbox "Background Image URL" you can set an image URL to use as a background which can act as a template for your drawings.

At the end of the page you'll find a textbox which changes with every drawing you make. It's a textual representation of your work. You can copy and save the text on your computer and load it back later on.

## Converting

Included is a crude PHP-script intended to convert SVG-files into shapes usable with this script. **This will probably not work for you.** Still want to give it a go? These are the Requirements:
* SVG-File must only contain paths with straight lines. No curves.
* Try to use only relative positioning. For Inkscape go to Edit⮕preferences⮕Input/Output⮕SVG output and set *Path string format* to *Relative*
* Currently the conversion script will scale your drawing to fill the screen and move it to top left.



## Ideas

Further Ideas to improve the script
* When multiple shapes are used try to automatically sort them to get the shortest distance for moves
* Possibility to delete or edit existing shapes without clearing everything
* Allow manually setting drawing speed further. Might help to tone down lines drawn due to repositioning of the beam
* Proper SVG converter
* Animations
