<?php
/**
 * @var \CommentApp\Response $this
 * @var \CommentApp\Models\Comment[] $comments
 */
?>
<DOCTYPE html>
<html>
<head>
    <title>Comments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?php $comments = $comments ?? []; ?>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal">Comments</h5>
    <form method="post" action="/logout">
        <button class="btn btn-outline-primary" type="submit">Logout</button>
    </form>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">
<?php if (!count($comments)): ?>  
    
    <h6>No message yet</h6>
        
<?php else: ?>
    <?php foreach($comments as $comment): ?>
    <div class="media text-muted pt-3"> 
          <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <strong class="d-block text-gray-dark"><?php echo $comment->getUser(); ?></strong>
            <?php echo $comment->getAttribute('body'); ?>
          </p>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
    <div class="my-3 p-3 bg-white rounded box-shadow">
    <form method="post">
        <input type="hidden" 
               value="<?php echo $this->getApp()->getRequest()->getParam('comment'); ?>"
               name="comment_source_hash">
        User: <?php echo $this->getApp()->getUser() ; ?>
        <div class="form-group">
            <label for="inputComment">Your comment here:</label>
            <textarea class="form-control" 
                      id="inputComment" 
                      aria-describedby="commentHelp"
                      name="comment_body"
                      placeholder="Write text here"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>