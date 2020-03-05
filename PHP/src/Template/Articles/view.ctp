<!-- File: src/Template/Articles/view.ctp -->

<h1><?= h($article->title) ?></h1>
<p><?= h($article->body) ?></p>
<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>

<?php
$this->extend('/Common/view');

$this->assign('title', $article->title);

$this->start('sidebar');
?>
<?php
echo $this->Html->link('edit', [
    'action' => 'edit',
    $article->id
]);
?>
<?php $this->end(); ?>
