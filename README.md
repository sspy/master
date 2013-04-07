Style Spy: The CSS Detective
============================

sspy is a command line utility that attempts to solve some common issues during CSS development.  When a website starts to become larger and more complex, the style rules and files that contribute and affect the site become more opaque.  It is sometimes difficult to tell, for example, if a change to a stylesheet will have unintended consequences on 'some' page because the selector matches unintended elements.  Style spy is kind of a Swiss Army utility for styles.

Here are some of the goals of this project.

* Provide a means to match all elements for a collection of DOMs, and return a report of all matches.  

In other words, using an arbritray selector like, e.g., '.foobar > li:first-child', return a results list of every element on your ENTIRE website that matches this.

Something like:

%> sspy --url=www.example.com --css=".foobar > li:first-child" --format="" -e (enumerate the matched elements)

Matched 23 elements on 11 pages

www.example.com/foo/bar.html

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

www.example.com/foo/barf.php

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

www.example.com/foo/barfly.php

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>
3.  

... etc..

* Locate selectors throughout your stylesheets, inline style, etc, that do not match any element on the entire site.

* Show elements who have classnames with no definition in any style sheet.  Be able to detect class names that are used as poor man reference handles in linked javascript.

* Frequency of each defined and used classes.  Provide a json stats format as well as a ascii terminal histogram.

* Show which style sheets contribute to a given arbitrary selector. E.g.:

%> sspy --url=www.example.com --css=".foobar > li:first-child" --find-ss

application.css:line 34
application-imports.css:line 55
application-imports.css:line 439

where those stylesheet locations contain rules that affect at least 1 property of elements that match the given arbitrary selector.

* Sometimes you may want to know which revision of a stylesheet had an affect on a certain collection of elements.  For example:

%> sspy --url=www.example.com --css="button" --show-changes -n=3



======

%> sspy --url=www.example.com --css=".foobar > li:first-child" --format="" -e (show elems)

Matched 23 elements on 11 pages

www.example.com/foo/bar.html

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

www.example.com/foo/barf.php

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

www.example.com/foo/barfly.php

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

...

(on the web)

Matched 23 elements on 11 pages

www.example.com/foo/bar.html   [show elements]

1.  <li>flapjacks</li>
2.  <li>fruity two shoes</li>

www.example.com/foo/barf.php   [show elements]
www.example.com/foo/barfly.php [show elements]

...




