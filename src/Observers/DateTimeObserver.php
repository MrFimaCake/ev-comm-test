<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Observers;

use CommentApp\Models\Model;
use CommentApp\Event;
/**
 * Called for child-classes of CommentApp\Models\Model
 */
class DateTimeObserver {
    
    /**
     * Set `created_at` and `updated_at` fields (like Timestamp behavior)
     * 
     * @param Event $event
     * @return type
     */
    public function onSave(Event $event)
    {
        $model = $event->getSubject();
        if (!($model instanceof Model)) {
            return;
        }
            
        /** @var Model $model */
        if (!$model->getIsExists() && $model->hasAttribute('created_at')) {
            $model->setAttribute('created_at', (new \DateTime)->format('Y-m-d H:i:s'));
        }
        if ($model->getIsExists() && $model->hasAttribute('updated_at')) {
            $model->setAttribute('updated_at', (new \DateTime)->format('Y-m-d H:i:s'));
        }
    }
}
