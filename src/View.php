<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

/**
 * Description of View
 *
 * @author mrcake
 */
class View {
    
    private $file;
    
    //put your code here
    public function setFile(string $filename)
    {
        $this->file = $filename;
    }
}
