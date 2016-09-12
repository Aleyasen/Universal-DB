# Universal-DB

Graph analytics algorithms leverage quantifiable structural
properties of the data to predict interesting concepts and
relationships. The same information, however, can be represented
using many different structures and the structural
properties observed over particular representations do not
necessarily hold for alternative structures. Because these
algorithms tend to be highly effective over some choices of
structure, such as that of the databases used to validate
them, but not so effective with others, graph analytics has
largely remained the province of experts who can find the
desired forms for these algorithms. We argue that in order
to make graph analytics usable, we should develop systems
that are effective over a wide range of choices of structural
organizations. We demonstrate Universal-DB an entity similarity
and proximity search system that returns the same
answers for a query over a wide range of choices to represent
the input database.

### [Univesal-DB Demo](http://universal-db.herokuapp.com)

## Publications
Main demo publication (VLDB 2015)
- Yodsawalai Chodpathumwan, Amirhossein Aleyasen, Arash Termehchy, Yizhou Sun, “Universal-DB: Towards Representation Independent Graph Analytics” [\[pdf\]](http://web.engr.oregonstate.edu/~termehca/universal-vldb15.pdf)

Other publications on this project:
- Yodsawalai Chodpathumwan, Amirhossein Aleyasen, Arash Termehchy, Yizhou Sun, Towards Representation Independent Similarity Search Over Graph Databases, CIKM, 2016. [\[pdf\]](http://localhost:81/Homepage/data/pubs/cikm16.pdf)
- Yodsawalai Chodpathumwan, Amirhossein Aleyasen, Arash Termehchy, Yizhou Sun, Representation Independent Proximity and Similarity Search. Technical Report. [\[pdf\]](http://arxiv.org/abs/1508.03763)
- Yodsawalai Chodpathumwan, Arash Termehchy, Yizhou Sun, Amirhossein Aleyasin, Jose Picado, Toward General Similarity Search Over Graphs, Graph Data Management Experiences & Systems (GRADES), 2014. [\[pdf\]](http://event.cwi.nl/grades2014/12-chodpathumwan.pdf)


## Requirement
 - PHP
 - Apache Server
 
For Windows, you can install XAMPP (https://www.apachefriends.org/index.html) that includes all the above (+MySQL+Perl), for OS X install MAMP (https://www.mamp.info/en/)

## Installation
 - Clone the project on the htdocs directory (in your xampp/mamp installation).
 - Start Apache Service
 - Check out http://localhost/Universal-DB/
