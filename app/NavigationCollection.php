<?php
/**
 * Created by PhpStorm.
 * User: BlackScorp
 * Date: 02.07.14
 * Time: 20:54
 */

namespace Logd\Core\App;


class NavigationCollection {
    /**
     * @var Navigation[]
     */
    private $elements = array();
    public function add(Navigation $navigation){
        $position = $navigation->position;
        if(!(bool)$position){
            $position=count($this->elements);
        }
        $this->elements[$position] = $navigation;
    }
    public function remove(Navigation $navigation){
        foreach($this->elements as $key => $element){
            if($navigation === $element){
                unset($this->elements[$key]);
            }
        }
    }
    public function findElementByText($text){
        foreach($this->elements as $element){
              if($element->text === $text){
                  return $element;
              }
        }
        return null;
    }
    /**
     * @return Navigation[]
     */
    public function getElements()
    {
        $result = array();
        foreach($this->elements as $element){
            $result[]=((array)$element);
        }
        return $result;
    }

} 