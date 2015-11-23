# ugly-todo-test
This is a skeleton for a todo list handler. 


# Structure & techs:
 - SlimFramework
 - Apache2
 - PHP 5.x
 - MySql 5.x
 - PDO
 - Composer
 - Bootstrap (cdn + local)
 - jQuery (cdn)
 - jQuery.UI (cdn)
 
# Install
 - Clone it && composer install
 - Just copy everything to the host

# Config
 - config.php >>> db details!
 - public/js/todolist.js >>> url

# Supported requests, API know-how
 - GET (Possible response: 200, 400, 404)
    - /getlist/
    - /removeitem/:id
        - Requested field: ID (int)
 - POST (Possible response: 200, 400, 404)
    - /setposition/
        - Requested field: list, serialized, list of id-s. 
    - /additem/
    
# Keep-in-mind
 - There is no design, skin or theme
 - Basic responsiveness added & tested
    - Tested on: Firefox (ubuntu, win7, win8), Chrome (ubuntu, win8)
 - Classical coding style with YODA style
