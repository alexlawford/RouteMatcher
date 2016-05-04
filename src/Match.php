<?php namespace AlexLawford\RouteMatcher;

class Match
{
    // Matches a pretty route (e.g users/string:name) to a uri string
    // returning an array of the matches. Array will be empty [] if
    // no matches are found
    public static function route(String $uri, String $route) : Array
    {        
        // Convert pretty routes to regex        
        $namedMatches = ['uri_string'];
            
        // wildcards
        if(strpos($route, ':') !== false) {
            $output = [];
        
            $array = explode('/', $route);
            
            foreach($array as $segment) {
                if(strpos($segment, ':') !== false) {
                    $match = explode(':', $segment);
                    // replace section with correct regex                    
                    switch($match[0]) {
                        case 'alpha':
                            $output[] = '([a-zA-Z]+)';
                            break;
                        case 'number':
                            $output[] = '([0-9]+)';
                            break;
                        case 'string':
                            $output[] = '([a-zA-Z0-9-_]+)';
                            break;
                        default:
                            return [];
                    }
                    // Save named matches for output later
                    $namedMatches[] = $match[1];
                } else {
                    $output[] = $segment;
                }  
            }
            $regex = self::bookend(implode('/', $output));
        } else {
            $regex = self::bookend($route);
        }
            
        $match = preg_match($regex, $uri, $matches);
        
        // apply our named keys to the matches
        // so, for example user/string:name
        // matching user/alex
        // would return "name" => "alex"
        if($match) {
            return self::applyKeys($namedMatches, $matches);
        }
        
        return $matches;
    }
    
    // Use the values in one linear array
    // as keys on a target array
    public static function applyKeys(Array $keys, Array $target) : Array
    {
        if(self::isAssociative($keys) || self::isAssociative($target)) {
            return [];
        }
        $result = [];
        for($i = 0; $i < count($target); $i++) {
            $result[$keys[$i]] = $target[$i];
        }    
        return $result;
    }
    
    // Check if an array is associative
    public static function isAssociative(Array $array) : Bool
    {
        return array_values($array) !== $array;
    }
    
    // For regex, put necessary bits before and after
    public static function bookEnd(String $route) : String
    {
        // add trailing slash if not already there
        if(substr($route, -1) !== '/') {
            $route .= '/';
        }
        //      starts with     ends with 0 or one /
        return '~^' . $route . '?$~';
    }
}