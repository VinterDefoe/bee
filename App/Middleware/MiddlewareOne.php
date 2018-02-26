<?php


namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class MiddlewareOne
{
    public $result = '';
//    public $arr = [
//        'one'=> [[2], 3 => 4],
//        'two'=> [2]
//    ];
    public $arr = [1,2];

    public function dump($arr,$tab = '')
    {
        $tab .= '*';
        if(is_array($arr)){
            foreach ($arr as $key=>$value){
                    $this->result .= $tab.'-'.$key.'</br>';
                    $this->dump($value,$tab);
            }
        }else{
            $this->result .= $tab.'-'.$arr.'</br>';
        }
    }

    /**
     *   $result = "<br>*<br>";
     */

    public function run()
    {
        $this->dump($this->arr);
        return $this->result;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('one', 1));
    }
}