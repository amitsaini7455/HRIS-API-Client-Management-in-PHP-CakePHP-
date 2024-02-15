<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HrisClient $hrisClient
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Tiles'), ['action' => 'edit', $hrisClient->id]) ?> </li>
        <li><?= $this->Html->link(__('List Tiles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Tiles'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="hrisClient view large-9 medium-8 columns content">
    <h3><?= h($hrisClient->title) ?></h3>
    <table class="vertical-table">
        
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= h($hrisClient->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified By') ?></th>
            <td><?= h($hrisClient->updated_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fields') ?></th>
            <td><?php foreach($clients as $k => $v){ echo $v['name'].' || '; }; ?></td>
        </tr>
    </table>
</div>
