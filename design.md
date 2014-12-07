# inLecture - Design

Many files have file specific information at the top, and many also have copious
commenting explaining what is happening, especially the PHP code. For specific
issues it may be illustrative to look at those comments.

But first, a visual representation of the files.

    . (root)
    \-- includes
    |   |-- config.php
    |   |-- constants.php
    |   |-- coreDogma.php
    |   |-- courseDogma.php
    |   `-- mapDogma.php
    \-- public (web root)
    |   \-- cache
    |   |   |-- courses.json
    |   |   `-- map.json
    |   \-- cssjs
    |   |   |-- scripts.js
    |   |   |-- scripts2.js
    |   |   |-- styles.css
    |   |   `-- styles2.css
    |   \-- json
    |   |   |-- listbuildings.php
    |   |   |-- query.php
    |   |   |-- test.php
    |   |   `-- update.php
    |   |-- index.php
    |   `-- index2.html
    \-- templates
    |   |-- footer.php
    |   |-- header.php
    |   |-- instructions.php
    |   |-- single.php
    |   `-- triple.php
    |-- design.md
    |-- design.md.html
    |-- documentation.md
    `-- documentation.md.html

The only files in the root directory are the required documentation and design
information, which you are most likely reading now. Both the markdown source and
the processed html is available.

### includes

The heavy lifting of the website occurs in the includes directory, particularly
the three functions files. In traditional form those files might be called
functions.php, but for ease of reference it was broken by type of function.

config.php includes all other files in the includes directory, and also
importantly ensures that all time functions in the website are using the correct
time zone.

constants.php is the main configuration file for deployment settings. There is
a spot for the CS50 API key assuming one wishes to have the website query the
API for each user request, but that is probably a bad idea. Instead, there is
also configuration settings for the location of the cache and the names of the
cache files. By default the website will read from those cache files.

coreDogma.php is where the general functions are defined, primarily the ones
that will query information directly from CS50 APIs and also read locally cached
JSONs. It also has the function to render html templates.

mapDogma.php has the defined functions dealing with processing arrays with
information from Harvard Maps API. Primarily it deals with locating things.

courseDogma.php is where the vast majority of the work is done. It's a very long
file, but all the functions defined therein are written in small building blocks
that come together in a big way. courseDogma deals with manipulating arrays from
CS 50 Courses API. Because the API does not allow queries by building or time,
it was necessary to load the entire dataset and write in that functionality.
There are thirteen defined functions in CourseDogma, each referring to each
other as needed to manipulate the arrays as needed. For more information about
each function, please see the comments around it. At the high level though,
courseDogma is where all the logic is.

### public/cache
Speaking of arrays and cached data, coureDogma reads (and feeds data into
coreDogma and mapDogma) from public/cache directory (in the provided setup at
least). Both of these jsons are dumps of the entire datasets from their
respective APIs. Again, this was necessary because local caching is recommended
for the sake of speed.

### index.php and the templates folder
This is where the user interface comes in. index.php is a controller that
renders instructions.php (along with header and footer) when there is no search
query, and single.php or triple.php when there is a search. header.php is where
the where the search form is defined, as it should be shown at all times. When
the user searches, index.php is reloaded but instead the controller renders
single.php if the user is not looking for classes recently out of session or
about to begin, and triple if the user is (it's triple because it'll render
three tables). index.php receives the data from the form, fills in defaults
if necessary, and calls functions from courseDogma (which in turn will call
other functions in courseDogma and also coreDogma) to return the data needed and
render in single or triple.php. instructions.php is rendered if the buildings
dropdown is left blank.

As a design decision, a lot of the supporting files, such as jQuery and such
are linked directly from CDN instead of locally. This decision was made in order
to simplify the local directories and reduce local resource usage.

### cssjs
The cssjs folder includes the custom CSS and JS. scripts and styles refer to
the JS and CSS needed for index2.html, and scripts2.js and styles2.css refer to
the JS and CSS for index.php.

### index2.html
There is a link at the bottom of index.php (well really footer.php in templates)
that links to the Google Maps version of the website. This site relies heavily
on scripts.js to render data in map form from things in the JSON directory

### JSON directory
To separate out files that returned JSON, they were put in a seperate directory.
listbuildings.php takes no input and returns a JSON list of all buildings that
are holding classes at all. It is actually not used. test.php is a dev file used
primarily to test the output of the many utility functions contained in
includes directory separate from the user interface. It's also very helpful in
illustrating the power of the defined functions. Since there are so many
functions that are only indirectly used by index.php and index2.html, test.php
is a great way to see output that isn't obvious, such as showing all courses
throughout the week at a given time, or all courses in a given building all day.

query.php and update.php are relied upon by scripts.js when using the Google
Maps version of the website. update.php takes lat/lng as inputs and returns
locations holding classes right now for the purposes of dropping pins, while
query.php takes the name of that building as input and returns the information
about the courses in session. 
