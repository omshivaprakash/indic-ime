=== Plugin Name ===
Contributors: vmonpara
Donate link: http://www.vishalon.net
Tags: Indic IME, Assamese, Bengali, Devanagari, Gujarati, Hindi, Marathi, Gurmukhi, Kannada, Malayalam, Manipuri, Nepali, Oriya, Punjabi, Sanskrit, Tamil, Telugu, transliteration, type, Indian, language, script
Requires at least: 2.0.2
Tested up to: 3.1
Stable tag: 2.5.2

Easily write in 9 Indian scripts with easy-to-use transliterate keyboard. Visitor/blog owner does not need to download any special software.

== Description ==

Using IndicIME plugin, you can very easily write in 9 Indian scripts: Bengali(Assamese, Bengali, Manipuri), Devanagari(Hindi, Marathi, Nepali, Sanskrit), Gujarati, Gurmukhi(Punjabi), Kannada, Malaylam, Oriya, Tamil and Telugu. It does not require any software download for writing in Indian script. Using this plugin, visitor can directly type in Indic script in search box or comment. Blog owner now can directly type title, tag, excerpts (in short everywhere) in his/her langauge of choice. If you need help in typing, a quick reference image is shown when clicked question mark icon. 

Typing is extremely easy. Typing follows "The way you speak, the way you type" rule. It is called transliteration. For example, choose "Devanagari" from the script list and try to if you type "bhaarat". You will see that depending on the key you pressed, it will automatically be converted into devanagari script. Half letters and special letters are also very easy to write. Try to type "prashna", "karma" or "padma" and you will see how easy it is to type in your favourite language. For reference of English and Indian script character mapping, please refer to the help by clicking on help (/question mark) icon. 

== Installation ==

1. Unzip the file on your local hard disk.
1. Upload all files to BLOGROOT/wp-content/plugins directory.
1. Activate via WordPress Plugins tab.
1. Go to Settings(/Options) Menu > Indic IME for setting your preference

Make sure that in your theme, in footer.php file wp_footer() function call exists.

== Frequently Asked Questions ==

= This plugin is not working at all =
Make sure that in your theme in footer.php file wp_footer() function call exists.
= Everything is correct but still I do not see IndicIME toolbar =
Your Web browser might have cached all your javascript. Make sure to purge cache by refreshing it. You may press ctrl+F5 to reload the page.
= After typing when I save it, it shows me questions marks(?) instead of Indian language text =
This is not a plugin problem. Your database must have a Unicode(UTF-8) collation. If you have brand new blog, create a database with proper collation. If you have existing blog, try searching for a plugin which will convert your existing blog content into Unicode.
== Screenshots ==

1. You will see a dropdown list with language of your choice. Choose one and start typing. For help, click on the button next to it.
2. On any page (including admin interface) this IndicIME toolbar will always be sticked on upper right corner.