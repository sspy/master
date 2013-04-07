Style Spy: The CSS Detective
============================

**sspy** is a command line utility that attempts to solve some common issues during CSS development.  When a website starts to become larger and more complex, the style rules and files that contribute and affect the site become more opaque.  It is sometimes difficult to tell, for example, if a change to a stylesheet will have unintended consequences on 'some' page because the selector matches unintended elements.  Style spy is kind of a Swiss Army utility for styles.

Capabilities
============

**Provide a means to match elements by a CSS selector for a collection of pages and return a report of all matches.**

In other words, using an arbitrary selector like, e.g., '.foobar > li:first-child', return a results list of every element on your ENTIRE website that matches this.

Something like:

%> sspy --url=www.example.com --css=".foobar > li:first-child" -e (enumerate the matched elements)

Matched 23 elements on 11 pages

www.example.com/foo/bar.html

1.  &lt;li&gt;first list item in some list that has on bar.html that matches the selector&lt;/li&gt;
2.  &lt;li&gt;first item from some other UL on the same page that matches&lt;/li&gt;
3. *The HTML for some other list item that matches*

www.example.com/foo/barfy.php

1. *Same as above.. the HTML that matches the selector enumerated in a list.*

**Locate selectors throughout your stylesheets, inline styles, etc, that do not match any element on the entire site.**

%> sspy --url=www.example.com --find-unused-style

**Show elements who have class names with no definition in any style sheet.  Be able to detect class names that are used as poor man reference handles in linked javascript.**

%> sspy --url=www.example.com --find-undef-class

**Frequency of each defined and used classes.  Provide a json stats format as well as a ascii terminal histogram.**

%> sspy --url=www.example.com --histo --output=terminal

td     application.css:line 255  Matched 2344 ***************************
p      application.css:line 25   Matched 1250 **********
table  application.css:line 5    Matched  100 ***
#hdr   application.css:line 2    Matched    1 *

**Show which style sheets contribute to a given arbitrary selector.**

%> sspy --url=www.example.com --css=".foobar > li:first-child" --find-ss

application-imports.css:line 54  ".foobar { border-co..."
application-imports.css:line 456 "li { color: red }"

where those stylesheet locations contain rules that affect at least one property of elements that match the given arbitrary selector.

**Show the revision of a stylesheet(s) had an affect on a certain collection of elements.**

%> sspy --url=www.example.com --css="button" --show-changes -n=3

56e05fced application.css  
214c44a37 third-party-styles.css application-imports.css  
dfc25a65c application.css application-imports.css main.css

In this example, these are the git versions that correspond to the change of at least 1 property of the matched elements, in this case every button on the entire website.  So maybe on revision 214c44a37, the corners of your buttons were inadvertently rounded by an included third-party stylesheet.  In addition a change that you made to the form padding moved the button to the right 10px.
