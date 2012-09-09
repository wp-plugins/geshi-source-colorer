=== Plugin Name ===
Contributors: flashpixx
Tags: code, color, geshi, syntax, syntax highlight, highlight, code color, source, programming, program
Requires at least: 3.2
Tested up to: 3.4.2
Stable tag: 0.11
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WCRMFYTNCJRAU
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html


The plugin can colorize any source in a post or page. There are a lot of possiblities to configurate your code designs.


== Description ==

The plugin creates a colorized view of source code. It uses <a href="http://qbnz.com/highlighter/">GeSHi</a> for creating the layout information and
allows an individual configuration of the tag values, so that a migration of anonther code syntax highlighter plugin is possible, without changing the article
data. Additional options are like enabling / disabling line numbers, copy to clipboard and open code in a blank window can be used by a hover toolbar.
The code blocks on a post / page can be collected to a "list of listings" automatically, each code block can get its own layout style and each code boxes
can be collapsed / expanded. On a single code box different lines can be highlighted or the highlighting can be added dynamically on hovering a individual DOM
element. Own styles for different codes can be created and exported / imported into the plugin.


= Features =

* free definition of the tags / shortcuts
* free code style definition on the given language
* multi- or singleline code block on one page with individual configuration
* global CSS style definition (code block and toolbar)
* static & dynamic highlighting (hover effect on every DOM with jQuery possible)
* table of listing on each page with anchor elements
* individual configuration on the code block hover toolbar with enabling / disabling line numbers, open code in a blank window, copy code to clipboard, collapsed view etc.
* collapsed view of the code with jQuery expand / collapsed action
* keyword references to the language definition
* individual code tab size
* export / import function with dynamic style renaming 
* automatic choice of the codestyle on the language name
* individual language definitions with GeSHi
* individual access with JavaScript and jQuery to the code blocks
* live preview of style definition


== Installation ==

1.  Upload the folder to the "/wp-content/plugins/" directory
2.  Activate the plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. source code view with highlight effect
2. source code with collapsed view and dynamic highlight effect
3. shows the black style
4. setting page with style definition


== Shortcode ==

The shortcuts / tags are free defined, so here you see the default definition "cc" and "cci". Feel free to change this names. You can define a own style, but you need to pass different placeholders in it,
so the plugin can create the correct expression for modifing the content. There are three placeholders:
<ul>
<li>%c this is the substitution for the source code</li>
<li>%p is the subsitution for the parameters, like a key-value pair. This placeholder represents the parameter string eg: key1="value" key2="value"</li>
<li>%s is a placeholder for any kind of spaces. You need this placeholder to create a space between the name of the tag and the parameter list. This placeholder represent one or more than one space</li>
</ul>
All meta characters are masked, so you can add a own tag strucutre. Take a look to the plugin's setting page, the defalut values are used within this documentation. Add to your post or page a tag
<pre>[cc lang="source language"]your source code[/cc]</pre> or the call <pre>[cci lang="source language"]your source code[/cci]</pre>
You can change in the global plugin option this tags / options, so you don't need a change to your articles, if you update from another plugin. The layout of the code is stored in the plugin options
(default values), this values can be overwritten by each code tag.


== Creating own code layout ==

This section should be a short how-to for creating your own layout style. 

<p>
First take a look on the "layout.css" in the plugin directory. There are three main section:
<ol>
<li>The first section descrips the layout of the table-of-listings (HTML ID "#geshisourcecolorer-lol"). The table is a div container with an unorderd (ul) list</li>
<li>The second section descriptes globally options:
    <ul>
        <li>"geshisourcecolorer-collapse-button" is the class name of the div container, which is shown if a code block is marked with "collapse=true"</li>
        <li>"geshisourcecolorer" is the global class name of each code block / line, so with this class you can set styles for all codes</li>
        <li>"toolbar" is the class, which is always within a "geshisourcecolorer", which descripes the toolbar div container</li>
        <li>"togglelinenumber" is the classname of the button on the toolbar for show / hide the line numbers</li>
        <li>"copyclipboard" is the classname of the button on the toolbar for clipboard-copy</li>
        <li>"sourcewindow" is the classname of the button for creating a blank-code-window</li>
    </ul>
    These settings can be combined so you can change or overwrite the default values. In the default settings the collapse-button and the images of the toolbar buttons are
    fixed, so each code block / line has got the same layout. With this structure colors and other layout styles can be seperated.
</li>
<li>The third section stores the default color styles of the code blocks / lines eg font-family, border layout, background color... There are two main classes:
    <ul>
        <li>"geshisourcecolorer-lines" which is the class name of each code line</li>
        <li>"geshisourcecolorer-block" which is the class name of each code block</li>
    </ul>
    So you can use these classes for creating the style of the container, in which the code is filled in. Both class names are referenced in the plugin settings under the "main options", so
    you can set in these options the default class name, but also you can use different styles on each code block with the parameter of the shortcuts eg:
    <pre>[cc lang="your language" css_block="classname"]your code[/cc]</pre>
    or for the code line
    <pre>[cci lang="your language" css_line="classname"]your code[/cci]</pre>
</li>
</ol>
The plugin has got two container styles that can be used:
<ul>
<li>"geshisourcecolorer-block" it is a style with white background and greyed line numbers</li>
<li>"geshisourcecolorer-block-black" a black style like Emacs, with black background and white line numbers</li>
</ul>
These CSS styles defines the <u>container layout</u> of the code bock / line only. <u>Store your own layout style in a CSS file within your theme directory with the name "geshi-source-colorer.css", because
the plugin tries to find the file and include the automatically.</u>
</p>

<p>The section above shows the layout of the container only, so the next step is the layout of the source code, which is filled in the container. The layout of the source code is
depended on the style option and/or the language option, which is set by the shotcut. The options "style" and "lang" are used for the style definition (see the FAQ). The style is loaded
in this order:
<ul>
    <li>if the style parameter is set, this style will be used if exists, if not exists the default style is used</li>
    <li>if the style parameter is not set, the lang value is used to find a style, if no style exists, the default style is used</li>
</ul>
To define the (language) styles, take a look into the plugin settings, exspecially the "codestyle" subsection. In this subsection each code style is defined. Each value is a CSS definition,
so you can use any CSS element to configurate the source code parts.
</p>



== Requirements ==

* Wordpress 3.2 or newer
* PHP 5.3.0 or newer 


== Changelog == 

= 0.12 =

* add class definition for styles
* add themeable JavaScript and CSS file
* add a black styles
* add tabbed code blocks

= 0.11 =

* fixing CSS layout errors
* fixing encoding errors and filter hook
* fixing copy-clipboard & create-blank-source-window layout errors
* sorting language option field on the settings page

= 0.1 =

* first version with the base functions


== Frequently Asked Questions ==

= Where can I find the tag options ? =
Take a look on the administration page of the plugin. Within the brackets [] you can find the option name, that can be passed to the tag. There
are also some options, which are set in the tag only:
<ul>
<li>highlight             : is used for static highlighting some code lines. Each line number (started with 1) is spitted by space [allowed values: spaces and numbers]</li>
<li>hoverhighlight        : this option can be set more than once and adds a dynamic hover effect to a DOM element for hovering different code lines [allowed valus: class / ID of the DOM element, line numbers split by spaces, CSS style definition]</li>
<li>keywordcase           : sets all keywords to lower or upper case. An empty value leaves the code untouched [allowed values: upper | lower | ""]</li>
<li>id                    : set the unique ID of the code block [allowed values: every string, default value: geshisourcecolorer-MD5 hash of the source] </li>
<li>style                 : sets the style of the code. If this option is not set, the plugin tries to find a style which is named with the language name (lower-case). If the style is not found, it uses the default style</li>
<li>lol                   : name of the source code that is shown within the list of listing (if this flag isn't set or empty, the code is not shown on the list) [allowed values: string value]</li>
<li>lolhead               : shows the name, which is set by the list of listing option [allowed values: true | false]</li>
<li>toolbar_blankwindow   : this option enables / disables the button for creating a text window with the source code [allowed values: true | false] </li>
<li>toolbar_copyclipboard : this option enables / disables the copt-to-clipboard button [allowed values: true | false]</li>
<li>toolbar_linenumber    : this option enables / disables the button for hiding / showing the line numbers [allowed values: true | false]</li>
</ul>

= Can I change the layout of the code box ? =
Yes, take a look on "Other Notes", there is a short description fpr changing the layout.


= Can I change the HTML ID of the code block ? =
Yes, see above. You can set the ID with the option flag "id" on the code tag.


= Can I get access to the line numbers with JavaScript ? =
Yes. Each line within a code block can be addressed with the ID name of the code block followed by "-line number".


= Can I add a table of listings ? =
Yes, you can create a "list of listings" with the call <pre>[lol]</pre>. This will add a div to your page and a jQuery call fills the source data into the
the div after the page have been loaded. The layout of this list is stored in the main CSS file. Feel free to change your layout.


= My language type is not supported. Can I add my own language file ? =
Yes, take a look on the <a href="http://qbnz.com/highlighter/geshi-doc.html#language-files">GeSHi documentation</a> and feel free to add your own style. The code file must be stored
within the "plugin-directory/external/geshi" directory.


= Can I use CSS styles on my code ? =
Yes, disable all layout information of GeSHi (set the "geshicss" flag to false globally or individual and the set the plugin options the "maincss" to false). In this case no stylesheet is
used, so you can create your own styles.


= Can I change the layout of the container ? =
Yes, the plugin has got two styles:
<ul>
<li>"geshisourcecolorer-block" it is a style with white background and greyed line numbers</li>
<li>"geshisourcecolorer-block-black" a black style like Emacs, with black background and white line numbers</li>
</ul>
Set up one of these styles on the plugin setting page in the main menu under "css class name of the code blocks container" (also the line style). 


= How can I include my own CSS style or JavaScript file ? =
The plugin can include your file automatically. Store your CSS file in your (parent) theme directory with the name "geshi-source-colorer.css". The JavaScript file
must be stored in the (parent) theme directory with the name "geshi-source-colorer.js". The plugin finds the files and include them.


= Where are the styles of the codes ? =
The plugin uses one default style for all codes, because defining different styles for each theme / theme group is not possible. Also the styles can be different on each language,
so there are to many possibilities to create the style definitions. But the plugin makes it easy to create your style, that can be used with your theme. Open the plugin settings and
the subsection "code styles". In this section you can set up on each code description an own CSS style. Please don't ask if I can create a style for you, because I don't know the 
colors of your site, so creating different predefined  styles are not usefull. <em>The plugin shows you a very simple method to create a style, so please do it yourself!</em> If you
have created a style and you think other people can use them also, send me the style definition, than I can add it to the plugin defaults.


= Can I change the layout of the box, in which the source code is shown ? =
Yes, at the moment there are two styles for code blocks:
<ol>
<li>"geshisourcecolorer-block" the default style with white background and greyed numbers</li>
<li>"geshisourcecolorer-block-black" a black version with black background and white numbers</li>
</ol>
You can set this main styles in the main options under class name. The code line can be also layouted, but I don't create a different style for code lines, because
the code line is flowed by the text, so only the "style of the source code" is used.


= Which HTML element is used by the code box ? = 
GeSHi uses in this plugin an ordered list (ol) and for each code line (li) different span tags. The full code is in a div tag, that uses the classes "geshisourcecolorer"
(especially "geshisourcecolorer-line" / "geshisourcecolorer-block") and a class named the language name. The toolbar is also stored in a div under the main code div. For
the full code, take a look into the DOM structure.


= Is the copy-to-clipboard function JavaScript only ? =
No, the copy-to-clipboard function uses JavaScript and Flash. Within the plugin <a href="https://github.com/jonrohan/ZeroClipboard">ZeroClipboard</a> is used. The main reason for this
call is the security structure in different browsers and internal access to the clipboard. A genereal call to copy data into the clipboard exists only in IE. Webkit browser like Safari,
Chrome can used Flash but Firefox is very secure that only Flash works with the copy procedure. A JavaScript only version is very complex, because the different implementations - if they exists - 
in different browser versions are not documented very well. So if you don't want to use a Flash object, you must disables the copy feature.


= Can I export / import my styles ? =
Yes, use the export / import function on the configuration panel. You can copy the "export value" into another plugin ("import value") or store this value into a text file or something else. 
All options of the plugin and the version of the plugin will be exported. The export is a serialized PHP array structure. You can import different version of the plugin, the values will be
converted into the correct data fields of the plugin.


= Will be removed my own styles on a plugin update ? =
The options of the plugin should be stored on update, but it is recommend to create an export of your settings befor you run the update. You should use in this case the export / import
section on the plugin option page, so you can copy the plugin settings into your clipboard, run the update, check the settings on the new version and import the settings if there are
some errors. 


= How can I highlight some code lines ? =
The line highlight works only on code blocks, not on code lines. There are three ways to do this. First the "static" way, that means, you can set the lines, which should be highlighted
in a flag of your code block. You can use this with the "highlight" flag (see above), eg (we are highlighting the lines 3, 5 and 12):
<pre>
[cc lang="cpp" highlight="3 5 12"]your code[/cc]
</pre>

On the second way you can add "hoverhighlight" to the code tag. This two tags in the example creates a hover effect on the ID (begins with #) and class (begins with .) elements for the lines
after the comma. The style of the hover effect is set in the third parameter, which is optional, if it is not set the default highlight style is used. The normal style is saved during hovering
and restored after, so you do not set the "normal style". Keep in mind, that this hovering will be created with jQuery. You can add more than once of this flag to the code tag, eg:
<pre>
[cc language="code language" hoverhighlight="#id, 2 3 4, background-color: #bcdaff;" hoverhighlight=".class, 9 16 20, background-color: #ff0000;"]
</pre>

Third way to highlight code lines is a jQuery effect, so you can set to a HTML tag an <a href="http://api.jquery.com/category/events/">event function</a>,
that highlights the lines on the event. It uses the ID structure of the lines (see above). You need a "document ready" call, which adds an action call for
the hover effect (other action are also possible, see <a href="http://api.jquery.com/category/events/">jQuery documentation</a>). In this example I put a
hover event on another HTML tag (referenced by the ID) and change the background color of the code lines. Use the 
<a href="http://api.jquery.com/category/selectors/">jQuery selectors</a> to get access to the elements.
<pre>
jQuery(document).ready(function(){

    // put a hover effect on the HTML element with the ID "myhoverelement"
    // and change the background color of the line 2 within the code block
    // with the ID "mycodeblock"
    jQuery("#myhoverelement").hover(
        
        // create the init function, that is called if the mouse moves in
        function() { jQuery("#mycodeblock-2").css("background-color", "#bcdaff"); },
        
        // create the release function, that is called if the mouse moves out
        function() { jQuery("#mycodeblock-2").css("background-color", "");   }
    );
}
</pre>
Take a look in which way you can get access with jQuery on the DOM elements, feel free to create you own effects. A special hint to jQuery is the use in the
"<a href="http://docs.jquery.com/Using_jQuery_with_Other_Libraries">no confilict mode</a>", so you can not use the $ sign to get access to the jQuery object. Take
a look to the WP documentation.


= I have got a nice layout, can you add this to the plugin ? =
Yes, please send me your CSS styles, a screenshot of the style types or the exported plugin values. 
