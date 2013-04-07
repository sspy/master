Style Spy: The CSS Detective
============================

Provide a means to match all elements for a collection of DOMs, and return a report of all matches.  

In other words, using an arbritray selector like .content-section ul > li:first-child, .footer, return a results list of every element on your ENTIRE website that matches this.

Unused selectors that are defined in style sheets. Show which sheet defines it.

Show elements who have classnames with no definition in any style sheet.

Frequency of each defined and used classes.

Show which style sheets contribute to the selector.

Different style sheet revisions. Basically, show elements who have a different style description (classname, for example) or different 'other' style between two style revisions.


usage
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


Future:

Unused selectors that are defined in style sheets. Show which sheet defines it.

Show elements who have classnames with no definition in any style sheet.

Frequency of each defined and used classes.

Show which style sheets contribute to the selector.

Different style sheet revisions. Basically, show elements who have a different style description (classname, for example) or different 'other' style between two style revisions.






