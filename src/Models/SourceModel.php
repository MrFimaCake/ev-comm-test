<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Models;

/**
 * Description of SourceModel
 *
 * @author mrcake
 */
class SourceModel extends Model {
    //put your code here
    
    public function attributes()
    {
        return ['id', 'title', 'comment_hash', 'created_at'];
    }
}
