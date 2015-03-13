=== Google Books Importer ===
Contributors: zarkodj
Tags: google books, import
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: 4.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Google Books Importer provides easy to use interface for import of books from books.google.com using Google's API 

== Description ==
This plugin imports data from books.google.com site using Google's API. Following data is pulled and can be imported to wordpress: 

id
<p>----volume info----</p>
<ul>
<li>title</li>
<li>subtitle</li>
<li>authors</li>
<li>publisher</li>
<li>publishedDate</li>
<li>description</li>
<li>pageCount</li>
<li>categories</li>
<li>averageRating</li>
<li>smallThumbnail</li>
<li>mediumImage</li>
<li>largeImage</li>
<li>thumbnail</li>
<li>language</li>
<li>previewLink</li>
<li>infoLink</li>
</ul>
-----sale info ---
saleability
isEbook
---- access info ---
viewability
epubacsTokenLink
pdfdownloadlink
webReaderLink

Admin page provides easy to use interface where you can map fields and choose where data is going to be stored. Values can be stored as posts ( or other post type ) in posts table, categories ( for "post" type only ) and custom fields as postmeta 



== Installation ==
1. Upload "google-books-importer.zip"  (unzipped) to the "/wp-content/plugins/" directory OR complete zip package in Plugins > Add New > Upload menu in admin of wordpress
2. Activate the plugin through the "Plugins" menu in WordPress.


== Screenshots ==
1. The screenshot description corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. The screenshot description corresponds to screenshot-2.(png|jpg|jpeg|gif).
3. The screenshot description corresponds to screenshot-3.(png|jpg|jpeg|gif).

== Changelog ==
= 1.0 =
* Initial release.

== Upgrade Notice ==
= 0.1 =
* Initial release.
