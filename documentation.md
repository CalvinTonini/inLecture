# inLecture - Documentation

## Introduction
inLecture is a web application that displays information about course lecture
time and locations. The website asks the user for a building, a day of week, and
a time of day. It also gives the option of displaying information about course
meetings that have just ended and are about to begin around a certain time as
well. However, only the name of building is mandated, the rest will default to
the current day and current time and not displaying additional data. There is
also a separate map interface as well, with some geo-location, but those
features are only able to show courses currently in session, with no ability to
simulate other days and times.

## Installation and Configuration
inLecture is written in PHP and HTML, and generally conforms to the MVC dogma.
Unpack the files such that the web root directory is "public", and "templates"
and "includes" need not be directly network accessible. That said, all files
should be accessible to the web server. Also, please ensure that your
web server supports the latest version of PHP. The web application, uses a local
cache to store information from Harvard Maps API and CS 50 Courses API. That
information should be configured in "constants.php", which is included in the
includes directory. For most installations, the only change required is changing
the CACHELOC definition to match that of the web directory of the cached files.
There is also the option of configuring your CS 50 API key so that the app will
directly query the relevant APIs. This is not necessary, and perhaps even not
advised. For more information please see the CS 50 API manuals.

Also, please ensure that your web server is network accessible, has all the
relevant security updates, and has an accurate system time. There should not be
any special configuration required.

## Usage

### Main Website

At the main page "index.php", there will be several forms at the top of the
screen. The first box is a dropdown of all buildings that are holding FAS
classes. The second box is a dropdown of days of the week, with a default of
the current day. The third box is a text field that allows date input. If the
browser supports javascript upon text box selection there will be a widget that
will assist in selecting a time, which should ideally be in the form "00:00",
and be in the 24 hour form. By default the day and time, if left alone, will
use the current day and current time. A building must be selected. There is
also a check box, which if selected will ask the site to generate additional
information about classes that had recently finished lecture or will be
starting lecture soon around the selected time.

Upon submitting the information the website will render the information
requested in table form.

### Map Form ALPHA

At the bottom of the main page there is a small link to a test version of a map
form the data. The system will attempt to use geo-location to initially center
on your current location. It will have a Google Maps layout with pins on top of
the relevant buildings with classes currently in session. Selecting those pins
will yield text boxes giving further information about the specific classes
in session. It is to be noted that the website will only render classes
currently in session; attempting to access the site on a weekend or at four
in the morning will yield a blank map. There is no way to simulate different
days or times of day.
