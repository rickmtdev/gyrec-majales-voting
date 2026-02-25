# General info
This is the original voting system code for the 2022 Majáles (Majáles naruby), written in late April 2022.

## Redactions
Some strings had to be redacted due to containing sensitive information. No other changes were made.

# Code commentary
My apologies to whoever decides to go through the code.

## General warcrimes commited
I decided not to use any frameworks since the app is relatively small. It should be noted that prior to me diving into coding PHP apps in Symfony, the majority of my PHP scripts were complete spaghetti code, full of whatever's the opposite of best practices.

You should take this and the fact that this code was written in a bit of a hurry into consideration when looking through it.

## Database querying
First and foremost, no, I did not know PDO is a thing. Secondly, I have no idea why I had to write the SQL connect part in every single script, instead of using includes. Other logic (such as the individual vote counts query) would have benefited from that as well.

SQL injection: notice the insert in `ajaxController.php`. No validation whether the choice param is a number is done; in fact, the comparisons in the if-clause would return true for a string starting with a number (ie "1 ... [sql prompt]").

## Other PHP stuff
Some logic was written with disregard to access control, such as the `admin.php` code. After a realization was made, output buffering was used instead of restructuring the code to prevent access to restricted parts of the code. Yes, the query runs on every script execution, regardless of the login.

`ajaxVoter` would probably be a more suitable name for the `ajaxController.php` script. Considering my shallow framework experience back then, you can see where I got the `controller` part from.

There is no reason for `vote.php` and `chart.php` to have the PHP extension.

App config (chart timer, chart counts toggle, voting lock) could probably have been implemented using a single-row SQL table, albeit having a config file that is being read/written to would also be viable. I have no idea why I decided to instead have one file per each setting. Even better to have them in the same directory as all the scripts.

## JS code
No generative AI was used when writing the code. GPT-3 was already out by then, but I did not find out until July 2022. I do not write much JS code and lately I've been lazily using Copilot for the majority of any JS-related stuff, so all I can say about the code is that at a first glance, it looks pretty okay, at least when compared to the BE.

I should note that although jQuery was originally included in the HTML, the final code is vanilla JS.

## HTML, CSS
The way the chart is designed for responsiveness felt sketchy even back then - it was made to be viewable on landscape fullscreen viewports with no other setups in mind. The way the JS is set up to keep the font in certain elements about the same size regardless of page zoom is also somewhat sus.

The `echo`-ed HTML in `admin.php` is atrocious.

The HTML structure in other scripts seems fine.